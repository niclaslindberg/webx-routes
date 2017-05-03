<?php

namespace WebX\Routes\Impl;

use WebX\Routes\Api\Path;

class PathImpl implements Path {

    private $currentSegmentPos;
    private $segments;
    private $path;

    private function initSegments() {
        if($this->currentSegmentPos===null) {
            $this->currentSegmentPos = 0;
            $this->segments = explode("/",trim($this->full()," /"));
        }
    }

    public function remainingSegments($skip=0) {
        $this->initSegments();
        return array_slice($this->segments,$this->currentSegmentPos + $skip);
    }

    public function moveCurrentSegment($dpos) {
        $this->currentSegmentPos += $dpos;
    }

    public function current() {
        $this->initSegments();
        return  $this->currentSegmentPos < count($this->segments) ? $this->segments[$this->currentSegmentPos] : null;
    }

    public function full() {
        if(!$this->path) {
            $this->path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
        return $this->path;
    }

}
