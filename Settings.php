<?php

$wgDBName = 'mediawiki';
$dockerMasterDb = [
	'host' => 'database',
	'dbname' => 'mediawiki',
	'user' => 'root',
	'password' => '',
	'type' => 'mysql',
	'flags' => DBO_DEFAULT,
	'load' => 0,
];
$dockerSlaveDb = [
	'host' => 'databasereplica',
	'dbname' => 'mediawiki',
	'user' => 'root',
	'password' => '',
	'type' => 'mysql',
	'flags' => DBO_DEFAULT,
	# Avoid switching to readonly too early (for example during update.php)
	'max lag' => 60,
	'load' => 1,
];
// Integration tests fail when run with replication, due to not having the temporary tables.
// So for integration tests just run with the master.
if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	$wgDBservers = [ $dockerMasterDb, $dockerSlaveDb ];
} else {
	$wgDBserver = $dockerMasterDb['host'];
	$wgDBuser = $dockerMasterDb['user'];
	$wgDBpassword = $dockerMasterDb['password'];
	$wgDBtype = $dockerMasterDb['type'];
}
$wgObjectCaches['redis'] = [
	'class' => 'RedisBagOStuff',
	'servers'=> [ 'redis:6379' ],
];
$wgMainCacheType = 'redis';
$wgSessionCacheType = 'redis';
$wgMainStash = 'redis';

$wgJobTypeConf['default'] = [
	'class' => 'JobQueueRedis',
	'redisServer' => 'redis:6379',
	'redisConfig' => [],
	'claimTTL' => 3600,
	'daemonized' => true
];
$wgShowHostnames = true;
