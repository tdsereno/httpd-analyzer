<?php

namespace Tdsereno\HttpdAnalyzer\Provider;

abstract class Base
{

    static $currentProvider = [];

    public abstract static function getType();

    protected static function getCurrentProvider()
    {
        return self::$currentProvider[static::getType()];
    }

    public static function setCurrentProvider($currentProvider)
    {
        self::$currentProvider[static::getType()] = $currentProvider;
    }

    /**
     * 
     * @return self
     */
    public static function getProvider()
    {
        static $cache = NULL;
        $provider = static::getCurrentProvider();
        if (!$provider)
        {
            throw new \Exception(ucfirst(static::getType()) . ' provider not fould');
        }
        if (!isset($cache[$provider]))
        {
            $cache[$provider] = new $provider();
        }
        return $cache[$provider];
    }

    /**
     * 
     * @param type $ip
     * @return \Tdsereno\HttpdAnalyzer\Model\Ip|boolean
     */
    public abstract static function get($ip);

    /**
     *
     * 
     * @return \Tdsereno\HttpdAnalyzer\Model\Ip
     */
    public abstract static function parse($json);
}
