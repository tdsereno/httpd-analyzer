<?php

namespace Tdsereno\HttpdAnalyzer\Model\Httpd;

class LogGroup
{

    protected $remoteHostname;
    protected $hitCount = 0;
    protected $size = 0;

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\Status[]
     */
    protected $status = [];
    protected $dates = [];
    protected $minDate, $maxDate;

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\Ip[]
     */
    protected $ips = [];

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\Method[]
     */
    protected $methods = [];

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\Url[]
     */
    protected $urls = [];

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\UserAgent[]
     */
    protected $browsers = [];
    protected $urlsSuspeitas = [];

    public function addUserAgent($value)
    {
        try
        {
            if (isset($this->browsers[$value]))
            {
                $this->browsers[$value]->addCount();
                return $this;
            }

            $this->browsers[$value] = (new \Tdsereno\HttpdAnalyzer\Model\UserAgent())->setUserAgent($value)->addCount();
            return $this;
        }
        catch (\Exception $ex)
        {
            \Tdsereno\HttpdAnalyzer\Printer::debug($ex->getMessage(), 1000);
        }
        return $this;
    }

    public function getBrowsers()
    {
        return $this->browsers;
    }

    public function addUrl($url)
    {
        $timer = microtime(TRUE);

        $url = (new \Tdsereno\HttpdAnalyzer\Model\Url(['url' => $url]));
        $url->loadInfo();

        if ($url->getIsSuspicious())
        {
            $urlSuspeita = $url->clone();
            if (!isset($this->urlsSuspeitas[$urlSuspeita->getUrl()]))
            {
                $urlSuspeita->setCount(0);
                $this->urlsSuspeitas[$url->getUrl()] = $urlSuspeita;
            }

            $this->urlsSuspeitas[$url->getUrl()]->addCount();
        }

        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('Method addUrl()', $timer);
        if (isset($this->urls[$url->getUrl()]))
        {
            $this->urls[$url->getUrl()]->addCount();
            return $this;
        }

        $this->urls[$url->getUrl()] = $url->addCount();
        return $this;
    }

    public function getUrlsSuspeitas()
    {
        return $this->urlsSuspeitas;
    }

    public function getUrls()
    {
        return $this->urls;
    }

    public function addMethod($method)
    {
        if (!isset($this->methods[$method]))
        {
            $this->methods[$method] = new \Tdsereno\HttpdAnalyzer\Model\Method(['method' => $method]);
        }

        $this->methods[$method]->addCount();

        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * 
     * @param type $ip
     * @return \Tdsereno\HttpdAnalyzer\Service\IpCacheProvider
     */
    public function getIpInfo($ip)
    {
        return \Tdsereno\HttpdAnalyzer\Service\IpCacheProvider::get($ip);
    }

    public function addIp($ip)
    {
        if (!isset($this->ips[$ip]))
        {
            $this->ips[$ip] = (new \Tdsereno\HttpdAnalyzer\Model\Ip())->setIp($ip);
        }
        $this->ips[$ip]->addCount();
        // $this->ips[$ip]->loadInfo();
        return $this;
    }

    public function getIps()
    {

        return $this->ips;
    }

    public function getMinDate()
    {
        return $this->minDate;
    }

    public function getMaxDate()
    {
        return $this->maxDate;
    }

    public function setMinDate($minDate)
    {
        $this->minDate = $minDate;
        return $this;
    }

    public function setMaxDate($maxDate)
    {
        $this->maxDate = $maxDate;
        return $this;
    }

    /**
     * Need Attention
     * @param type $date
     * @return $this
     */
    public function addDate($date)
    {
        $timer = microtime(TRUE);
        if (!$date)
        {
            return $this;
        }

        if (!$this->getMinDate())
        {
            $this->setMaxDate($date)
                    ->setMinDate($date);
        }
        else
        {
            $timeStamp = date_create($date);
            $min = date_create($this->getMinDate());
            $max = date_create($this->getMaxDate());

            if ($timeStamp < $min)
            {
                $this->setMinDate($date);
            }

            if ($timeStamp > $max)
            {
                $this->setMaxDate($date);
            }
        }

        $this->dates[] = $date;
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('addDate', $timer);
        return $this;
    }

    public function addStatus($status)
    {
        if (!isset($this->status [$status]))
        {
            $this->status[$status] = (new \Tdsereno\HttpdAnalyzer\Model\Status(['status' => $status]));
        }
        $this->status[$status]->addCount();
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHitCount()
    {
        return $this->hitCount;
    }

    public function getSizeFormated()
    {
        return \Tdsereno\HttpdAnalyzer\Helper::parseBytes($this->size);
    }

    public function getRemoteHostname()
    {
        return $this->remoteHostname;
    }

    public function setRemoteHostname($remoteHostname)
    {
        $this->remoteHostname = $remoteHostname;
        return $this;
    }

    public function addHit()
    {
        $this->hitCount++;
        return $this;
    }

    public function addSize($size)
    {
        if (!is_numeric($size))
        {
            return $this;
        }
        $this->size += $size;
        return $this;
    }

    public function sort()
    {
        $timer = microtime(TRUE);
        uasort($this->urlsSuspeitas, function ($a, $b) {
            return $b->getCount() - $a->getCount();
        });
        uasort($this->browsers, function ($a, $b) {
            return $b->getCount() - $a->getCount();
        });
        uasort($this->urls, function ($a, $b) {
            return $b->getCount() - $a->getCount();
        });
        uasort($this->ips, function ($a, $b) {
            return $b->getCount() - $a->getCount();
        });
        uasort($this->status, function ($a, $b) {
            return $b->getCount() - $a->getCount();
        });
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('sorting data', $timer);
    }

    public function printStatus()
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- Codigo HTTP:', 20, STR_PAD_RIGHT);
        foreach ($this->getStatus() as $status)
        {

            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $status->getStatus() . ' => ' . $status->getCount() . 'x', 20);
        }
    }

    public function printMethods()
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- METHODS:', 20, STR_PAD_RIGHT);
        foreach ($this->getMethods() as /* @var $method \Tdsereno\HttpdAnalyzer\Model\Method */ $method)
        {
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $method->getMethod() . ' => ' . $method->getCount() . 'x', 20);
        }
        \Tdsereno\HttpdAnalyzer\Printer::newLine(PHP_EOL);
    }

    public function printIps($maxDepth)
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- TOP ' . $maxDepth . ' IPS:', 20, STR_PAD_RIGHT);
        \Tdsereno\HttpdAnalyzer\Printer::newLine();

        foreach (array_slice($this->getIps(), 0, $maxDepth) as /* @var $ip Model\Ip */ $ip)
        {
            $ip->loadInfo();

            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $ip->getIp(), 20);
            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getCount() . 'x ', 10);

            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getIp() . ' ', 20, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getCity() . ' ', 20, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getRegion() . ' ', 20, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getCountry() . ' ', 20, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug($ip->getOrg() . ' ', 30, STR_PAD_RIGHT);

            /*            if ($ip->getConsultedOn())
              {
              \Tdsereno\HttpdAnalyzer\Printer::debug('Consulta Online: ', 20);
              \Tdsereno\HttpdAnalyzer\Printer::debugTextYellow($ip->getConsultedOn() . ' ', 40, STR_PAD_RIGHT);
              }
              else
              {
              \Tdsereno\HttpdAnalyzer\Printer::debug('Cached: ', 20);
              \Tdsereno\HttpdAnalyzer\Printer::debugTextGreen($ip->getCachedOn() . ' ', 40, STR_PAD_RIGHT);
              } */


            \Tdsereno\HttpdAnalyzer\Printer::newLine();
        }
    }

    public function printUrl($maxDepth)
    {
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- TOP ' . $maxDepth . ' URL:', 20, STR_PAD_RIGHT);
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        // print_r($this->getUrls());
        foreach (array_slice($this->getUrls(), 0, $maxDepth) as $url)
        {
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $url->getUrl(), 80, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug('  => ' . $url->getCount() . 'x', 20);
            \Tdsereno\HttpdAnalyzer\Printer::newLine();
        }
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
    }

    public function printUrlSuspeita($maxDepth)
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- TOP ' . $maxDepth . ' URL Suspeitas:', 30, STR_PAD_RIGHT);
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        foreach (array_slice($this->getUrlsSuspeitas(), 0, $maxDepth) /* @var $url \Tdsereno\HttpdAnalyzer\Model\Url */as $url)
        {
            \Tdsereno\HttpdAnalyzer\Printer::debugTextRed(' ' . $url->getUrl(), 80, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $url->getCount() . 'x ', 10);
            \Tdsereno\HttpdAnalyzer\Printer::debugTextRed(' ' . $url->getSuspiciousReason(), 50, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::newLine();
        }



        \Tdsereno\HttpdAnalyzer\Printer::newLine();
    }

    public function printBrowser($maxDepth)
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debug('-- TOP ' . $maxDepth . ' BROWSER:', 20, STR_PAD_RIGHT);
        \Tdsereno\HttpdAnalyzer\Printer::newLine();

        foreach (array_slice($this->getBrowsers(), 0, $maxDepth) as $browser)
        {
            $browser->loadInfo();
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $browser->browser, 30, STR_PAD_RIGHT);
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $browser->getCount() . 'x', 30);
            // \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $data->device, 30);
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $browser->getType(), 15);
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $browser->getOs(), 30);
            \Tdsereno\HttpdAnalyzer\Printer::debug(' ' . $browser->getUserAgent(), 55);
            \Tdsereno\HttpdAnalyzer\Printer::newLine();
        }
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
    }

    public function printMainInfo()
    {
        \Tdsereno\HttpdAnalyzer\Printer::newLine();
        \Tdsereno\HttpdAnalyzer\Printer::debugTextBgGreen($this->getRemoteHostname(), 30);

        \Tdsereno\HttpdAnalyzer\Printer::debug($this->getMinDate(), 20);
        \Tdsereno\HttpdAnalyzer\Printer::debug(' - ', 3);
        \Tdsereno\HttpdAnalyzer\Printer::debug($this->getMaxDate(), 20);
        \Tdsereno\HttpdAnalyzer\Printer::debug($this->getSizeFormated(), 9);
        \Tdsereno\HttpdAnalyzer\Printer::debugTextGreen($this->getHitCount() . ' hits ', 20);
        \Tdsereno\HttpdAnalyzer\Printer::debug(count($this->getIps()) . ' ips únicos', 20);
        $count = count($this->getIps());
        if ($count > 0)
        {
            \Tdsereno\HttpdAnalyzer\Printer::debug(floor($this->getHitCount() / $count) . ' média de his por ip', 40);
        }
    }

    public function print($maxDepth = 20)
    {

//        Cache\IpCache::loadCache();
        //      Cache\UserAgentCache::loadCache();

        $cols = exec('tput cols') - 1;

        \Tdsereno\HttpdAnalyzer\Printer::debug(str_pad('-', $cols, '-'), $cols);

        $this->printMainInfo();
        $this->printIps($maxDepth);
        $this->printStatus();
        $this->printMethods();
        $this->printUrl($maxDepth);
        $this->printUrlSuspeita($maxDepth);
        $this->printBrowser($maxDepth);

        // \Tdsereno\HttpdAnalyzer\Printer::debug(str_pad('-', $cols, '-'), $cols);
        //     Cache\IpCache::saveCache();
        //   Cache\UserAgentCache::saveCache();
    }

}
