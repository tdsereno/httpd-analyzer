<?php

require_once 'vendor/autoload.php';
/*
  $provider = \Tdsereno\HttpdAnalyzer\Service\IpInfoProvider::getCurrentServiceProvider();
  $provider = new $provider();
  $result2 = $provider->getIpInfo('1.1.1.1');

  print_r(json_encode($result2));
 */

/*
  \Tdsereno\HttpdAnalyzer\Service\IpCacheProvider::loadCache();
  $provider = \Tdsereno\HttpdAnalyzer\Service\IpCacheProvider::get('8.8.1.1');
  \Tdsereno\HttpdAnalyzer\Service\IpCacheProvider::saveCache();
 */
/*
  $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
  $dotenv->load();
  $s3_bucket = $_ENV['S3_BUCKET'];
  var_dump($s3_bucket);
  $s3_bucket = getenv('S3_BUCKET');
  var_dump($s3_bucket);
  die(123);
 * 
 * */
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

\Tdsereno\HttpdAnalyzer\Provider\Ip\Base::setCurrentProvider(getenv('IP_PROVIDER'));
\Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base::setCurrentProvider(getenv('USERAGENT_PROVIDER'));

print_r(\Tdsereno\HttpdAnalyzer\Provider\Ip\Base::getProvider());
print_r(\Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base::getProvider());
print_r(\Tdsereno\HttpdAnalyzer\Provider\Ip\Base::getProvider());
