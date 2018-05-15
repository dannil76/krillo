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

$config = \Zend\Config\Factory::fromFiles( glob( $configPaths, GLOB_BRACE | GLOB_NOSORT ) );

// API TOKEN
if( !$config['api']['token'] ) $config['api']['token'] = getToken();

use \Slim\Slim;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Slim\Middleware\SessionCookie;
use \danNil76\Rest\Client as RestClient;

// Create app
$app = new Slim( $config['slim'] );
$app->view( new Twig() );
$app->view()->parserOptions = $config['twig'];
$app->view()->parserExtensions = array( new TwigExtension() );

// Add the session cookie middleware *after* auth to ensure it's executed first
$app->add( new SessionCookie( array( 'secret' => '=?!front242' ) ) );

$app->container->singleton('apiClient', function() use($config) {
	return RestClient::create([
		'service'	=> $config['api']['service'],
		'base_uri'	=> $config['api']['base_url'],
		'token'		=> $config['api']['token']
	]);
});
