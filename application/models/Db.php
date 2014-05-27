<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Date: 4/17/14
 * Time: 3:28 PM
 */

/**
 * Class Application_Model_Db
 */
class Application_Model_Db
{

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function toArray()
    {
        $reflectionClass = new ReflectionClass(get_class($this));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }
        return $array;
    }
}