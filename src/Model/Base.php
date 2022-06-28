<?php

namespace Tdsereno\HttpdAnalyzer\Model;

abstract class Base implements \JsonSerializable
{

    private $count = 0;

    public function __construct($attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function setAttributes($attributes = [])
    {
        if ($attributes instanceof \stdClass)
        {
            $attributes = (array) $attributes;
        }

        foreach ($attributes as $name => $value)
        {
            if (property_exists($this, $name))
            {
                $this->{$name} = $value;
            }
        }
    }

    public function getAttributes()
    {
        $class = new \ReflectionClass($this); // get class object
        $properties = $class->getProperties(); // get class properties
        $ownProperties = array();

        foreach ($properties as $property)
        {
            $key = $property->getName();
            $ownProperties[$key] = $this->$key;
        }
        return $ownProperties;
    }

    public function clone()
    {
        $clone = new static();
        $clone->setAttributes($this->getAttributes());
        return $clone;
    }

    public function addCount()
    {
        $this->count++;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public abstract function loadInfo();

    public function jsonSerialize()
    {
        $result = new \stdClass();

        foreach ((array) $this as $property => $value)
        {
            $property = preg_match('/^\x00(?:.*?)\x00(.+)/', $property, $matches) ? $matches[1] : $property;
            $result->$property = $value;
        }
        // avoid cache no cached properyties
        /* if (isset($result->consultedOn))
          {
          //unset($result->consultedOn);
          } */
        if (isset($result->count))
        {
            unset($result->count);
        }
        return $result;
    }

}
