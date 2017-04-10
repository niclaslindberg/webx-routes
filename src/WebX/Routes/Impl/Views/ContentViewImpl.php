<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\ResponseTypes\ContentView;
use WebX\Routes\Api\ResponseTypes\DownloadResponseType;
use WebX\Routes\Api\ResponseWriter;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Impl\Views\FilenameMimetypeFactory;

class ContentViewImpl implements ContentView
{
    private $file;

    private $fileName;

    private $content;

    private $contentType = "text/plain";

    private $disposition;

    public function __construct() {}

    public function setDisposition($fileName, $disposition = self::DISPOSITION_ATTACHMENT) {
        $this->fileName = $fileName;
        $this->disposition = $disposition;
        return $this;
    }

    public function setFile($file, $contentType = null) {
        if(file_exists($file)) {
            $this->file = $file;
            if ($contentType) {
                $this->contentType = $contentType;
            } else {
                $this->contentType = FilenameMimetypeFactory::findMimeType($file);
            }
            return $this;
        } else {
            throw new RoutesException("File not found");
        }
    }

    public function setContent($content, $contentType = null) {
        $this->content = $content;
        if($contentType) {
            $this->contentType = $contentType;
        }
        return $this;
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        if($this->disposition) {
            $responseHeader->addHeader("Content-Disposition", "{$this->disposition};filename={$this->fileName}");
        }
        $responseHeader->addHeader("Content-Type", $this->contentType);
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        if($this->content!==NULL) {
            $responseBody->writeContent($this->content);
        } else if ($this->file!==NULL) {
            if(file_exists($this->file)) {
                if(filesize($this->file)>2 * 1024 * 1024) {
                    $fp = fopen($this->file, 'r');
                    while(!feof($fp)) {
                        $responseBody->writeContent(fread($fp, 4096));
                    }
                    fclose($fp);
                } else {
                    $responseBody->writeContent(file_get_contents($this->file));
                }
            } else {
                throw new RoutesException("File {$this->file} does not exist");
            }
        } else {
            throw new RoutesException("View has no fileName nor content");
        }
    }

    public function fileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function disposition($disposition)
    {
        $this->disposition = $disposition;
        return $this;
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }


}
