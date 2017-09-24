<?php

namespace WebX\Routes\Extras\Twig\Impl;
use Twig_Environment;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Extras\Twig\Api\TwigFactory;
use WebX\Routes\Extras\Twig\Api\TwigView;
use WebX\Routes\Impl\ArrayUtil;


class TwigViewImpl implements TwigView,TwigFactory {

    private $contentType;

    private $id;

    private $suffix = ".twig";

    private $data;

    private $twig;

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

    public function twig() {
        if(!$this->twig) {
            if($templatesPath = $this->routes->resourcePath("templates")) {
                $loader = new \Twig_Loader_Filesystem([$templatesPath]);
                $this->twig = new \Twig_Environment($loader, []);
            } else {
                throw new RoutesException("/templates folder does not exist.");
            }

        }
        return $this->twig;
    }


    public function renderBody(ResponseBody $responseBody, $data) {
        if($this->id) {
            $twig = $this->twig();
            $data = ArrayUtil::mergeRecursive($data,$this->data);
            $responseBody->writeContent($twig->render("{$this->id}{$this->suffix}",is_array($data) ? $data : []));
        } else {
            throw new RoutesException("Missing id in twig reponse");
        }
    }

    public function id($template) {
        $this->id = $template;
        return $this;
    }

    public function contentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }

    public function data($data, $path = null) {
        if (null !== $path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            if (!is_array($this->data)) {
                $this->data = [];
            }
            $root = &$this->data;
            while ($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
        return $this;
    }


}