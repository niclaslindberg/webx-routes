<?php

namespace WebX\Route\Impl\Responses;
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 8:11 AM
 */
use WebX\Route\Api\Responses\TemplateResponse;
use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\ResponseWriter;

class TemplateResponseImpl extends AbstractResponse implements TemplateResponse
{
    private $data;

    private $template;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
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
                $root[$part] = empty($path) ? $data : [];
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
        if ($templatePath = $configuration->get("templatePath")) {
            $loader = new \Twig_Loader_Filesystem($templatePath);
            $twig = new \Twig_Environment($loader, $configuration->get("options"));
            $templateId = $this->template ?: $configuration->get("defaultTemplate");
            if($configurator = $configuration->get("configurator")) {
                call_user_func_array($configurator,[$twig]);
            }
            $responseWriter->addContent($twig->render("{$templateId}.twig",$this->data ?: []));
        } else {
            throw new \Exception("Missing templatePath in twig view render config");
        }
    }
}