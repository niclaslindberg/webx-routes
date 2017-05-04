<?php

namespace WebX\Routes\Impl;

use WebX\Routes\Api\Path;
use WebX\Routes\Api\RoutesException;

class PathImpl implements Path {

    private $currentSegmentPos;
    private $segments;
    private $path;

    public function __construct()  {
        $this->currentSegmentPos = 0;
        $this->segments = explode("/",trim($this->full()," /"));
    }

    public function remainingSegments() {
        return array_slice($this->segments,$this->currentSegmentPos);
    }

    public function moveCurrentSegment($dpos) {
        $this->currentSegmentPos += $dpos;
    }

    public function pop($segment) {
        if($segment===null) {
            return 0;
        } else if(is_string($segment)){
            if ($segment === $this->current()) {
                $this->moveCurrentSegment(1);
                return 1;
            } else if ($segment === '*') {
                $this->moveCurrentSegment(1);
                return 1;
            }
            return null;
        } else {
            throw new RoutesException("Segment condition is not a string.");
        }
    }

    public function next() {
        $pos = $this->currentSegmentPos + 1;
        return  $pos < count($this->segments) ? $this->segments[$pos] : null;
    }

    public function reset($steps) {
        $this->currentSegmentPos-=$steps;
    }

    public function current() {
        return  $this->currentSegmentPos < count($this->segments) ? $this->segments[$this->currentSegmentPos] : null;
    }

    public function full() {
        if(!$this->path) {
            $this->path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
        return $this->path;
    }

}
