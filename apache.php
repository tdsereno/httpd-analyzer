<?php

require_once 'vendor/autoload.php';

$start = microtime(true);
set_time_limit(0);
ini_set('memory_limit', '2048M');

$analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();
//$analyzer->addFile(__DIR__ . '/logs/no_domain_acess.log');
// $analyzer->setDistintDomain(TRUE);

$analyzer->setGroupBy('All Domains Agrouped');
//https://stackoverflow.com/a/30939678/14626048
foreach (glob(__DIR__ . '/logs/*.{*}', GLOB_BRACE) as $filename)
{
    $analyzer->addFile($filename);
}
// \Tdsereno\HttpdAnalyzer\Printer::setAvoidColor(TRUE);
$analyzer->setMaxDepth(10);

//$mainRequest->setMinDate('24/Jun/2022:23:30:00 -0300');
//$mainRequest->setMaxDate('24/Jun/2022:23:59:00 -0300');
//$mainRequest->setFilter('www.myDomain.com.br');
$analyzer->load();

$result = $analyzer->getLogGroup();

/*
print_r($result);
die();*/

$analyzer->print();

$analyzer->printTimer();
$analyzer->printLinesInfo();

