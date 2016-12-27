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
    private $statusCode;
    private $cookieFile;

    public function __construct($indexFile) {
        $this->port = 8000;

        $currentDir = getcwd();
        $rootDir = substr($indexFile,strlen($currentDir)+1);
        $rootDir = substr($rootDir,0,strrpos($rootDir,"/"));

        $this->cmd = "php -S localhost:{$this->port} -t " . $rootDir;

        $this->cookieFile = sys_get_temp_dir() . "/test_" . rand(0,100000) . time() . ".cookies";


        $this->start();
        usleep(200*1000);

    }

    public function get_contents($path="/") {
        $url = "http://localhost:{$this->port}{$path}";
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
        curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->cookieFile );
        curl_setopt( $ch, CURLOPT_COOKIEFILE, $this->cookieFile );
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        $this->statusCode = $info["http_code"];
        curl_close($ch);
        return $data;
    }

    /**
     * @return mixed
     */
    public function statusCode()
    {
        return $this->statusCode;
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