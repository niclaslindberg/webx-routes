<?php

namespace WebX\Routes\Impl;

class PathImpl {

    private $currentSegmentPos;
    private $segments;
    private $path;

    private function initSegments() {
        if($this->currentSegmentPos===null) {
            $this->currentSegmentPos = 0;
            $this->segments = explode("/",trim($this->full()," /"));
        }
    }

    public function nextSegment() {
        $this->initSegments();
        if(count($this->segments)>$this->currentSegmentPos) {
            return $this->segments[$this->currentSegmentPos];
        }
        return null;
    }

    public function remainingSegments($skip=0) {
        $this->initSegments();
        return array_slice($this->segments,$this->currentSegmentPos + $skip);
    }

    public function moveCurrentSegment($dpos) {
        $this->currentSegmentPos += $dpos;
    }

    public function currentSegment() {
        if($this->currentSegmentPos!==null) {
            return $this->segments[$this->currentSegmentPos];
        }
    }

    public function full() {
        if(!$this->path) {
            $this->path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
        return $this->path;
    }

}
