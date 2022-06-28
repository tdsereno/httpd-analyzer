<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Url;

/**
 * native php parse_url
 */
class ParseUrl extends Base
{

    public static function parse($json)
    {
        //print_r($json);
        return (new \Tdsereno\HttpdAnalyzer\Model\Url($json));
        //->setCachedOn($json->cachedOn);
    }

    /**
     * 
     * @param \Tdsereno\HttpdAnalyzer\Model\Url $url
     * @return boolean
     */
    public static function get($url)
    {
        if (!$url->getUrl())
        {
            return FALSE;
        }

        $parsed = parse_url($url->getUrl());
        list($urlMin, $query) = [$parsed['path'] ?? '', $parsed['query'] ?? ''];

        $result = new \stdClass();
        $result->url = $urlMin;
        $result->path = $query;
        if (list($motivo, $pattern) = self::isSuspeita($url->getUrl()))
        {
            $result->isSuspicious = TRUE;
            $result->suspiciousReason = $motivo;
        }

        return self::parse($result);
    }

    public static function isSuspeita($url)
    {
        $timer = microtime(TRUE);
        $operators = array(
            'select * ' => 'SQL INJECTION',
            'select ' => 'SQL INJECTION',
            'union all ' => 'SQL INJECTION',
            'union ' => 'SQL INJECTION',
            ' all ' => 'SQL INJECTION',
            ' where ' => 'SQL INJECTION',
            ' and 1 ' => 'SQL INJECTION',
            ' and ' => 'SQL INJECTION',
            ' or ' => 'SQL INJECTION',
            ' 1=1 ' => 'SQL INJECTION',
            ' 2=2 ' => 'SQL INJECTION',
            ' -- ' => 'SQL INJECTION',
            //'vendor' => 'Acesso vendor',
            'index.php/loja-virtual/manufacturer/' => ' Ataque conhecido (manufacturer)',
            'logon.aspx' => ' Ataque conhecido (logon.aspx)',
            'owa/auth/x.js' => ' Ataque conhecido (auth/x.js)',
            'microsoft.exchange.ediscovery.exporttool.application' => ' Ataque conhecido (microsoft exchange)',
            '.env' => ' Ataque conhecido (Leitura .env)',
            '.well-known' => 'Ataque conhecido (/.well-known)',
            'config.php' => ' Ataque conhecido config.php',
            'invokefunction' => 'Ataque conhecido (native call php)',
            'call_user_func_array' => 'Ataque conhecido (native call php)',
            'invokefunction' => 'Ataque conhecido (native call php)',
            'invokefunction' => 'Ataque conhecido (native call php)',
            '.git' => 'Ataque conhecido (git acess)',
            '.cgi' => 'Ataque conhecido (cgi)',
            'vpn' => 'Ataque conhecido (vpn)',
            'Xdebug_SESSION_START' => 'Ataque conhecido (php Xdebug)',
            'logon/LogonPoint' => ' Ataque conhecido (logon/LogonPoint)',
            'fgt_lang' => ' Ataque conhecido (fgt_langt)',
            '../..' => 'Ataque conhecido (massive dir)',
            'autodiscover' => 'Ataque conhecido (autodiscover)',
            'mifs' => 'Ataque conhecido (mifs)',
            'system_api' => 'Ataque conhecido (system_api )',
            'streaming' => 'Ataque conhecido (streaming)',
            'stream/live' => 'Ataque conhecido (stream/live)',
            'stalker_portal' => 'Ataque conhecido (stalker_portal)',
            'version.js' => 'Ataque conhecido (version.js)',
            'bash' => 'Ataque conhecido (bash)',
            'wp-includes' => ' Ataque conhecido (wp-includes)',
            'wlwmanifest.xml' => ' Ataque conhecido (wlwmanifest.xml),',
            'phpinfo' => ' Ataque conhecido (phpinfo)',
            '\x' => ' Ataque conhecido (\x)',
            'org.apache' => 'Ataque conhecido (org.apache)',
            'phpmyadmin' => 'Ataque conhecido (phpmyadmin)',
            '/php' => 'Ataque conhecido (/php)'
        );

        /* @todo, melhor perfomance
          $pattern = '/(' . implode('|', array_key($operators)) . ')/';
          \Tdsereno\HttpdAnalyzer\Printer::debug($pattern, 20);
          if (preg_match($pattern, $url))
          {
          \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('Urls suspeita3)', $timer);
          return [$motivo, $operator];
          } */
        foreach ($operators as $operator => $motivo)
        {
            if (/* preg_match("/" . $operator . "/i", $url) */ strpos($url, $operator) !== FALSE)
            {
                \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('Urls suspeita (strpos)', $timer);
                return [$motivo, $operator];
            }
        }

        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('Urls suspeita', $timer);
        return FALSE;
    }

}
