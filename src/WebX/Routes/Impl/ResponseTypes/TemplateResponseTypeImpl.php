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

    private $id;

    private $prefix;
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
        if($this->id) {
            $response->header("Content-Type", $this->contentType ?: "text/html; charset=utf8");
            $response->status(200);
        }
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        $data = is_array($data) ? $data : [];
        $templatesDir = $configuration->asString("templatesDir");
        if ($templatePath = $this->resourceLoader->absolutePath($templatesDir)) {
            if ($this->id) {
                $loader = new \Twig_Loader_Filesystem($templatePath);
                $twig = new \Twig_Environment($loader, $configuration->asArray("options", []));
                if ($configurator = $configuration->asAny("configurator")) {
                    call_user_func_array($configurator, [$twig]);
                }
                $suffix = $configuration->asString("suffix");
                $prefix = $this->prefix ?: "";
                $responseWriter->addContent($twig->render("{$prefix}{$this->id}.{$suffix}", $data));
            } else {
                throw new ResponseException("Id missing in TemplateResponse");
            }
        } else {
            throw new ResponseException(sprintf("The templates folder %s does not exist", $templatesDir));
        }
    }

    public function id($template)
    {
        $this->id = $template;
        return $this;
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }


}