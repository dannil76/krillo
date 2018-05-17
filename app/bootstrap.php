<?php
date_default_timezone_set( 'Europe/Stockholm' );
setlocale( LC_ALL, 'sv_SE' );

require_once APPLICATION_PATH . '/app/vendor/autoload.php';

if( !defined('SLIM_MODE') )
{
	$env = getenv('SLIM_MODE') ?: 'production';
	define( 'SLIM_MODE', $env );
}

$configPaths = sprintf(
    "%s/app/config/{,*.}{global,%s,local}.php",
    APPLICATION_PATH,
    SLIM_MODE
);

$config = \Zend\Config\Factory::fromFiles(
	glob( $configPaths, GLOB_BRACE | GLOB_NOSORT )
);

if( is_file($config['api']['token']) )
{
	$config['api']['token'] = file_get_contents( $config['api']['token'] );
}
