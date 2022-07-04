Apache2/Httpd Log Analyzer - PHP
=
## Whats is
A tool to analyze logs from an apache web server, with one or more domains, example output:
<img src="https://github.com/tdsereno/httpd-analyzer/blob/main/result_example.png">

## Requirements
 + PHP 7.3.0 or newer 
## Getting started
First, download it and install depedencies

    git clone https://github.com/tdsereno/httpd-analyzer.git
    cd httpd-analyzer
    composer install --ignore-platform-reqs
    php -f apache.php 

 All start in instance the analyzer, like this:

    $analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();

  So, you can add log files, like this:

    $analyzer->addFile(__DIR__ . '/logs/no_domain_acess.log');

Add a folder this:

      foreach (glob(__DIR__ . '/logs/*.{*}', GLOB_BRACE) as $filename)
      {
          $analyzer->addFile($filename);
      }

Set the max depth for show results:     

    $analyzer->setMaxDepth(40);    

So, after add the logs, you can proccess all files:

    $analyzer->load();
Output this in terminal:

    $analyzer->print();

   Get the object and made yourself analityc, its return a array of Model\Httpd\LogGroup

    $result = $analizer->getLogGroup();
  Output to txt

    php -f apache.php > file.txt


Filter a domain or a log file (if dont use the canonical Server name in log)

    $mainRequest->setFilter('www.mydomain.com.br');
    $mainRequest->setFilter('mydomain_access.log');

Filter a date period

    $mainRequest->setMinDate('24/Jun/2022:23:30:00 -0300');
    $mainRequest->setMaxDate('24/Jun/2022:23:59:00 -0300');

## Log Format
According the httpd documentation,
https://httpd.apache.org/docs/2.4/mod/mod_log_config.html#examples
Default Log Format - Common Log Format (CLF)
(Dont have the the canonical ServerName of the server serving the request)

    "%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\""

Suggestion: (Have the The canonical ServerName of the server serving the request)

    "%v %h %l %u %t  \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\""

In cent os, the conf file is loccated in:

    /etc/httpd/conf/httpd.conf

On ubuntu is:

    /etc/apache2/apache2.conf

If you dont wanna change the log format, you can separete each ServerName im a log file



## Perfomance
I tested with with more than real 50 log files, in  size of 3.7Gb

In a digital ocean droplet, with 1 VCPU Shared, 1GB RAM and SSD (using SWAP) -  8179 lines per seconds

    Processed 57 files with size of 3740.6MB In a total of 12412537 lines with success on 12412537 lines in 1517 seconds about 8179 lines per seconds

In a digital ocean droplet, with 2 VCPU Decidated, 8GB RAM and SSD - 47784 lines per seconds
    
    Processed 57 files with size of 3740.6MB In a total of 12412537 lines with success on 12412537 lines in 259 seconds about 47784 lines per seconds
