<?php

namespace WebX\Routes\Impl\ResponseTypes;
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 8:11 AM
 */
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\ResourceLoader;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;
use WebX\Routes\Api\ResponseWriter;

class TemplateResponseTypeImpl implements TemplateResponseType
{
    private $contentType;

    private $template;

    /**
     * @var ResourceLoader
     */
    private $resourceLoader;

    public function __construct(ResourceLoader $resourceLoader)
    {
        $this->resourceLoader = $resourceLoader;
    }

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Type", $this->contentType ?: "text/html; charset=utf8");
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        $templatesDir = $configuration->asString("templatesDir");
        if ($templatePath = $this->resourceLoader->absolutePath($templatesDir)) {
            if ($this->template) {
                $loader = new \Twig_Loader_Filesystem($templatePath);
                $twig = new \Twig_Environment($loader, $configuration->asArray("options", []));
                if ($configurator = $configuration->asAny("configurator")) {
                    call_user_func_array($configurator, [$twig]);
                }
                $suffix = $configuration->asString("suffix");
                $responseWriter->addContent($twig->render("{$this->template}.{$suffix}", $data));
            } else {
                throw new ResponseException("Template not set in TemplateResponse");
            }
        } else {
            throw new ResponseException(sprintf("The templates folder %s does not exist", $templatesDir));
        }
    }

    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }


}