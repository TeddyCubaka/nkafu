<?php

class ModelInterface
{
    private static $environment;
    private static $model_nomenclature;
    public function __construct()
    {
        self::$environment = $_ENV['ENVIRONMENT'];
        self::$model_nomenclature = $_ENV['MODEL_METHOD_NOMENCLATURE'];
    }

    /**
     * This function hydrate an instance of this class.
     * It work by using the setters methods of the extends class.
     * Make sure you're models has setters for all your attribute.
     * Note : this function call the setter if the one of data's keys match with the setter.
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method =
                self::$model_nomenclature == 'SNAKE' ? 'set_' . $key : 'set' . ucfirst($key);
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    /**
     * This method return all the attribute and and data of instance of this class. 
     * You must also know that it return value by getters of attribute. If your attribute doesn't have any getter, it's will not be in the returned array.
     */
    public function data()
    {
        $data = [];

        $reflectionClass = new ReflectionClass($this);
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $methodName = $method->getName();


            if (strpos($methodName, 'get') !== 0) {
                continue;
            }

            $propertyValue = $this->$methodName();

            // if ($propertyValue !== null) {
            $propertyName = self::$model_nomenclature == 'SNAKE' ? (substr($methodName, 4)) : lcfirst(substr($methodName, 3));

            if (is_object($propertyValue)) {
                $data[$propertyName] = $propertyValue->getID();
            } else {
                $data[$propertyName] = $propertyValue;
            }
            // }
        }

        return $data;
    }
}
