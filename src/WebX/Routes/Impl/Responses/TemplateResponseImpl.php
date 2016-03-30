<?php

namespace WebX\Routes\Impl\Responses;
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 8:11 AM
 */
use WebX\Routes\Api\ResourceLoader;
use WebX\Routes\Api\ResponseHost;
use WebX\Routes\Api\Responses\TemplateResponse;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\ResponseWriter;

class TemplateResponseImpl extends AbstractResponse implements TemplateResponse
{
    private $data;

    private $template;

    /**
     * @var ResourceLoader
     */
    private $resourceLoader;

    public function __construct(ResponseHost $responseHost, ResourceLoader $resourceLoader) {
        parent::__construct($responseHost);
        $this->resourceLoader = $resourceLoader;
        $this->setContentType("text/html; charset=utf-8");
    }


    public function setData($data, $path = null)
    {
        if($path) {
            if(is_string($path)) {
                $path = explode(".",$path);
            }
            if($this->data === null) {
                $this->data = [];
            }
            $root = &$this->data;
            while($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
        $this->setContentAvailable();
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        $this->setContentAvailable();
    }

    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter)
    {
        $templatesDir = $configuration->asString("templatesDir");
        if ($templatePath = $this->resourceLoader->absolutePath($templatesDir)) {
            if($this->template) {
                $loader = new \Twig_Loader_Filesystem($templatePath);
                $twig = new \Twig_Environment($loader, $configuration->asArray("options",[]));
                if ($configurator = $configuration->asAny("configurator")) {
                    call_user_func_array($configurator, [$twig]);
                }
                $responseWriter->addContent($twig->render("{$this->template}.twig", $this->data ?: []));
            } else {
                throw new \Exception("Template not set in TemplateResponse");
            }
        } else {
            throw new \Exception(sprintf("The templates folder %s does not exist",$templatesDir));
        }
    }
}