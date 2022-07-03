<?php

namespace Tdsereno\HttpdAnalyzer;

class Analyzer
{

    protected $files = [];

    /**
     * 
     * @var \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup[]
     */
    protected $logGroup = [];
    protected $filter = FALSE;
    protected $maxDepth = 10;
    protected $minDate, $maxDate;
    protected $distintDomain = FALSE;
    protected $totalSize = 0;
    protected $groupBy = FALSE;

    public function __construct()
    {
        Helper::loadEnv();
    }

    private function getGroupBy()
    {
        return $this->groupBy;
    }

    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    private function getDistintDomain()
    {
        return $this->distintDomain;
    }

    public function setDistintDomain($distintDomain)
    {
        $this->distintDomain = $distintDomain;
        return $this;
    }

    /**
     * 
     * @return Model\Httpd\LogGroup[]
     */
    public function getLogGroup(): array
    {
        return $this->logGroup;
    }

    public function setLogGroup(array $logGroup)
    {
        $this->logGroup = $logGroup;
        return $this;
    }

    private function getMinDate()
    {
        return $this->minDate;
    }

    private function getMaxDate()
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

    private function getMaxDepth()
    {
        return $this->maxDepth;
    }

    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
        return $this;
    }

    private function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function addFile($file)
    {
        $this->files[] = $file;
        self::addFileSizeTotal(filesize($file));
        return $this;
    }

    private function getApacheLogParser()
    {
        static $cache = NULL;
        if ($cache)
        {
            return $cache;
        }
        if ($this->getDistintDomain())
        {
            $logFormat = "%v %h %l %u %t  \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"";
            return $cache = new \BenMorel\ApacheLogParser\Parser($logFormat);
        }

        $logFormat = "%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"";
        return $cache = new \BenMorel\ApacheLogParser\Parser($logFormat);
    }

    public function addLogData($line, $fileName)
    {
        if (!$line)
        {
            return $this;
        }
        $timer = microtime(TRUE);

        try
        {
            // @todo multiple log format, detect it
            $result = $this->getApacheLogParser()->parse($line, TRUE);
        }
        catch (\Exception $ex)
        {
            return $this;
        }


        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('parsing logs file', $timer);
        // @todo Regex // problema com extensão @todo
        //   preg_match('/([\w]+)[\s](.+)(\.[\w]+) /', $result['firstRequestLine'], $matches);
        $timer1 = microtime(TRUE);
        preg_match('/([\w]+)[\s](.+)(?:\.[\w]+)? /', $result['firstRequestLine'], $matches);
        list($full, $method, $url, $extension) = array_pad($matches, 4, NULL);
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('extractin url info (preg_match)', $timer1);
        if ($this->getFilter() && $this->getFilter() != $result['serverName'])
        {
            return $this;
        }

        $timeStamp = date_create($result['time']);
        if ($this->getMaxDate())
        {
            if ($timeStamp > date_create($this->getMaxDate()))
            {
                return $this;
            }
        }

        if ($this->getMinDate())
        {
            if ($timeStamp < date_create($this->getMinDate()))
            {
                return $this;
            }
        }
        $name = $fileName;
        if ($this->getDistintDomain())
        {
            $name = $result['serverName'] ?? $name;
        }

        if ($this->getGroupBy())
        {
            $name = $this->getGroupBy();
        }
        if (!isset($this->logGroup[$name]))
        {
            $this->logGroup[$name] = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        }

        $min = date_create($this->getMinDate());
        $max = date_create($this->getMaxDate());


        $this->logGroup[$name]
                ->setRemoteHostname($name)
                ->addHit()
                ->addUrl($url)
                ->addMethod($method)
                ->addStatus($result['status'])
                ->addUserAgent($result['requestHeader:User-Agent'] ?? 'Without User Agent')
                ->addIp($result['remoteHostname'])
                ->addDate($result['time'])
                ->addSize($result['responseSize'] ?? '');
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('method addRequestData (parse+process)', $timer);
        return $this;
    }

    static private $logInfo = [
        'parsedLines' => 0,
        'totalLines' => 0,
        'sizeReaded' => 0,
        'totalSize' => 0
    ];

    private static function addParsedLine()
    {
        self::$logInfo['parsedLines']++;
    }

    private static function addTotalLine()
    {
        self::$logInfo['totalLines']++;
    }

    private static function addFileSizeReaded($size)
    {
        self::$logInfo['sizeReaded'] += $size;
    }

    private static function addFileSizeTotal($size)
    {
        self::$logInfo['totalSize'] += $size;
    }

    private static function getCurrentProgress()
    {
        return Helper::parseBytes(self::$logInfo['sizeReaded']) . ' / ' . Helper::parseBytes(self::$logInfo['totalSize']);
    }

    private static function getLogInfo()
    {
        return self::$logInfo;
    }

    private function processSingleFile($file)
    {
        $res = explode('/', $file);
        $fileName = end($res);
        //Printer::memoryUsage();
        Printer::debug('Processando ' . $file);
        if ($fh = fopen($file, "r"))
        {
            $left = '';
            $block = 1 * (1024 * 1024); //1MB or counld be any higher than HDD block_size*2s
            while (!feof($fh))
            {// read the file
                $timerFile = microtime(TRUE);
                $temp = fread($fh, $block);
                self::addFileSizeReaded($block);
                $fgetslines = explode("\n", $temp);
                $fgetslines[0] = $left . $fgetslines[0];
                if (!feof($fh) && isset($lines))
                {
                    $left = array_pop($lines);
                }
                \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('Reading file', $timerFile);
                // Printer::debug(date("Y-m-d H:i:s") . ' - Reading some lines . ' . self::getCurrentProgress());
                Printer::replaceOut(date("Y-m-d H:i:s") . ' - Reading some lines . ' . self::getCurrentProgress());
                foreach ($fgetslines as $k => $line)
                {
                    self::addTotalLine();
                    try
                    {
                        $timerRequest = microtime(TRUE);
                        $this->addLogData($line, $fileName);
                        // Printer::debug('Add log data  ' . $file);
                        // Printer::memoryUsage();
                        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('method addRequestData', $timerRequest);
                        self::addParsedLine();
                    }
                    catch (\Exception $ex)
                    {
                        /* echo ($ex->getMessage());
                          throw $ex; */
                    }
                }
            }
            fclose($fh);
        }
        else
        {
            throw new \Exception('Falha ao ler arquivo');
        }
    }

    public function load()
    {
        $timer = microtime(TRUE);

        foreach ($this->getFiles() as $file)
        {
            // Printer::debug("Processing: $file size " . Helper::parseBytes(filesize($file)));
            $this->processSingleFile($file);
        }
        $this->sort();
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('All', $timer);
    }

    private function sort()
    {

        usort($this->logGroup, function ($a, $b) {
            return $b->getHitCount() - $a->getHitCount();
        });
        foreach ($this->logGroup as /* @var $request GroupedRequest */ $request)
        {
            $request->sort();
        }
    }

    public function print()
    {
        Printer::replaceOut('');
        foreach ($this->logGroup as /* @var $r GroupedRequest */ $key => $r)
        {
            $r->print($this->getMaxDepth());
        }
    }

    public function printTimer()
    {
        foreach (Timer::getElapsedTimers() as $name => $timer)
        {
            Printer::debug($name, 25, STR_PAD_RIGHT);
            Printer::debug(number_format($timer, 4) . ' sec ', 15);

            Printer::debug(PHP_EOL);
        }
    }

    public function printLinesInfo()
    {
        $info = self::getLogInfo();

        $size = round($info['totalSize'] / 1024 / 1024, 1) . 'MB';

        Printer::debugN('Processed ' . count($this->getFiles()) . ' files ');
        Printer::debugN('with size of ' . $size . ' ');
        Printer::debugN('In a total of ' . count($this->getLogGroup()) . ' domains ');
        Printer::debugN('in a total of ' . $info['totalLines'] . ' lines ');
        Printer::debugN('with success on ' . $info['parsedLines'] . ' lines ');
        Printer::debugN('in ' . floor(Timer::get('All')) . ' seconds ');
        Printer::debugN('about ' . floor(($info['totalLines'] / Timer::get('All'))) . ' lines per seconds ');
    }

}
