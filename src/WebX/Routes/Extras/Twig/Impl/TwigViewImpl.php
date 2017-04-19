<?php

namespace WebX\Routes\Impl\ResponseTypes;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Extras\Twig\Api\TwigView;


class TwigViewImpl implements TwigView {

    private $contentType;

    private $id;

    private $suffix = ".twig";

    private $data;

    /**
     * @var Routes
     */
    private $routes;

    public function __construct(Routes $routes) {
        $this->routes = $routes;
        if(!class_exists('\Twig_Environment')) {
            throw new RoutesException("Twig response requires Twig library on the class path.");
        }
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        if($this->id) {
            $responseHeader->addHeader("Content-Type", $this->contentType ?: "text/html; charset=utf8");
            $responseHeader->setStatus(200);
        }
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        if($this->id) {
            if($templatesPath = $this->routes->resourcePath("templates")) {
                $loader = new \Twig_Loader_Filesystem($templatesPath);
                $twig = new \Twig_Environment($loader, $loader);
                $responseBody->writeContent($twig->render("{$this->id}{$this->suffix}",($this->data && $data) ? array_merge($data,$this->data) : ($data ? $data : $this->data)));
            } else {
                throw new RoutesException("Template {$this->id} not found");
            }
        } else {
            throw new RoutesException("Missing id in twig reponse");
        }
    }

    public function id($template)
    {
        $this->id = $template;
        return $this;
    }

    public function contentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }

    public function data(array $data) {
        $this->data = $data;
    }


}