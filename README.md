Apache2 Log Analyzer - PHP
=

## Requirements

+ PHP 7.3.0 or newer
## Whats is
Uma ferramenta para analizer logs de um servidor Web apache, com um ou mais dominios

## What i can do
All start in instance the analyzer, like this:

    $analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();
So, you can add log files, like this:

    $analyzer->addFile(__DIR__ . '/logs/no_domain_acess.log');
or add a folder this:

     foreach (glob(__DIR__ . '/logs/production/*') as $filename)
     {
	       $mainRequest->addFile($filename);
     }
Set the max depth for show results:     

    $analyzer->setMaxDepth(40);

     
So, after add the logs, you can proccess all files:

    $analyzer->load();

Output this in terminal:


    $analyzer->print();

Output to txt

      php -f apache.php > file.txt



Filter a domain or a log file (if dont use the canonical Server name in log)

    $mainRequest->setFilter('www.mydomain.com.br');
    $mainRequest->setFilter('mydomain_access.log')

Filter a date period

    $mainRequest->setMinDate('24/Jun/2022:23:30:00 -0300');
    $mainRequest->setMaxDate('24/Jun/2022:23:59:00 -0300');

## Log Format
According the httpd documentation
https://httpd.apache.org/docs/2.4/mod/mod_log_config.html#examples
Common Log Format (CLF) (Dont have the 	The canonical ServerName of the server serving the request)
|"%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\""|  |
|--------------------------------------------------------------|--|
|                                                              |  |

Suggestion: (Have the The canonical ServerName of the server serving the request)

    "%v %h %l %u %t  \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\""

In cent os, the conf file is loccated in:

    /etc/httpd/conf/httpd.conf

On ubuntu is:

    /etc/apache2/apache2.conf

If you dont wanna change the log format, you can separete each ServerName im a log file

You can do what you want, in yout php, like this:

Get the object response

Output in terminal


## Installing

    git clone https://github.com/tdsereno/httpd-analyzer.git
    cd httpd-analyzer
    composer install
    php -f apache.php 

## Perfomance
I tested with with more than 20 log files, with maxDepth 40
In a digital ocean droplet, with 1 VCPU, 1GB RAM and SSD
Docker running with WSL in a i5-7400 3.0GHZ

## Getting started

## License

This project is open-sourced software licensed under the [GPL license](https://www.gnu.org/copyleft/gpl.html)

## Credits

