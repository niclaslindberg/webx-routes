<?php




namespace WebX\Routes\Impl;

use WebX\Ioc\Ioc;
use WebX\Routes\Api\ControllerException;

class ControllerFactory {

    /**
     * @var array[array]
     */
    private $classNameArrayList;

    public function __construct() {
        $this->classNameArrayList = [];
    }

    public function pushClassNamespaces(array $classNames) {
        array_unshift($this->classNameArrayList,$classNames);
    }

    public  function popClassNamespaces($n = 1) {
        $this->classNameArrayList = array_slice($this->classNameArrayList,$n-1);
    }

    public function createClassName($classNameEnd) {
        foreach($this->classNameArrayList as $classNames) {
            if ($classNames) {
                foreach ($classNames as $classNameStart) {
                    $className = $classNameStart . '\\' . $classNameEnd;
                    if (class_exists($className)) {
                        return $className;
                    }
                }
            }
        }
        if (class_exists($classNameEnd)) {
            return $classNameEnd;
        }
        throw new ControllerException("Could not find controller {$classNameEnd}");
    }
}