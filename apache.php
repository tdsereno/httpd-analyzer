<?php

require_once 'vendor/autoload.php';

$start = microtime(true);
set_time_limit(0);
ini_set('memory_limit', '2048M');

$mainRequest = new \Tdsereno\HttpdAnalyzer\Analyzer();

$mainRequest->addFile(__DIR__ . '/logs/access.log');
/*
  foreach (glob(__DIR__ . '/logs/production/*') as $filename)
  {
  $mainRequest->addFile($filename);
  }
 */


// \Tdsereno\HttpdAnalyzer\Printer::setAvoidColor(TRUE);
$mainRequest->setMaxDepth(40);

//$mainRequest->setMinDate('24/Jun/2022:23:30:00 -0300');
//$mainRequest->setMaxDate('24/Jun/2022:23:59:00 -0300');
//$mainRequest->setFilter('www.myDomain.com.br');
$mainRequest->load();

$mainRequest->print();

$mainRequest->printTimer();
$mainRequest->printLinesInfo();

