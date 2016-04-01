<?php
/**
 * User: niclas
 * Date: 3/24/16
 * Time: 12:52 PM
 */

namespace Test\WebX\Web;


class WebServer
{

    private $process;
    private $cmd;
    private $port;

    public function __construct($indexFile) {
        $this->port = 8000;

        $currentDir = getcwd();
        $rootDir = substr($indexFile,strlen($currentDir)+1);
        $rootDir = substr($rootDir,0,strrpos($rootDir,"/"));

        $this->cmd = "php -S localhost:{$this->port} -t " . $rootDir;
        #echo($this->cmd);
        $this->start();
        usleep(100*1000);

    }

    public function get_contents($path="/") {
        $url = "http://localhost:{$this->port}{$path}";
        return file_get_contents($url);
    }

    public function start() {
        if(!$this->process) {
            $this->process = new Process($this->cmd);
        }
    }

    public function stop() {
        if($this->process) {
            $this->process->stop();
            $this->process = null;
        }
    }
}