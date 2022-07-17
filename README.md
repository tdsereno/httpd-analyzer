

Apache2/Httpd Log Analyzer - PHP
=
A tool to analyze logs from an apache web server, with one or more domains and multiple log formats.
Example output:
<img src="https://github.com/tdsereno/httpd-analyzer/blob/main/result_example.png">

## Requirements
 + PHP 7.3.0 or newer 
## Getting started
Download it standalone and install depedencies

    git clone https://github.com/tdsereno/httpd-analyzer.git
    cd httpd-analyzer
    composer install --ignore-platform-reqs
    php -f apache.php 

Or, install via [Composer](https://getcomposer.org/): 

    composer require tdsereno/httpd-analyzer

 All start in instance the analyzer, like this:

    $analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();

  So, you can add log files, like this:

    $analyzer->addFile(__DIR__ . '/logs/no_domain_acess.log');

Add a folder:

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

    $analizer->setFilter('www.mydomain.com.br');
    $analizer->setFilter('mydomain_access.log');

Filter a date period

    $analizer->setMinDate('24/Jun/2022:23:30:00 -0300');
    $analizer->setMaxDate('24/Jun/2022:23:59:00 -0300');

## Log Format
In case of servers that host more than one domain, it is natural to want to analyze the accesses, individually, by default, according to the [httpd documentation](https://httpd.apache.org/docs/2.4/mod/mod_log_config.html#examples), the default log format is:

Default Log Format - Common Log Format (CLF)

    "%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\""

My suggestion: (Have the The canonical ServerName of the server serving the request)

    "%v %h %l %u %t  \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\""

In cent os, the conf file is loccated in:

    /etc/httpd/conf/httpd.conf

On ubuntu is:

    /etc/apache2/apache2.conf

If you don't wanna change the log format, you can separete each ServerName im a log file



## Perfomance
I tested with more than real 50 log files, in  size of 3.7Gb

In a digital ocean droplet, with 1 VCPU Shared, 1GB RAM and SSD (using SWAP) -  8179 lines per seconds

    Processed 57 files with size of 3740.6MB In a total of 12412537 lines with success on 12412537 lines in 1517 seconds about 8179 lines per seconds

In a digital ocean droplet, with 2 VCPU Decidated, 8GB RAM and SSD - 47784 lines per seconds
    
    Processed 57 files with size of 3740.6MB In a total of 12412537 lines with success on 12412537 lines in 259 seconds about 47784 lines per seconds
