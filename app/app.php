<?php

// Prepare app
require_once APPLICATION_PATH . '/app/bootstrap.php';

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
$app->add( new SessionCookie( array( 'secret' => '=?!front242' ) ) );

// Setup api client
$app->container->singleton('apiClient', function() use($config) {
	return RestClient::create([
		'service'	=> $config['api']['service'],
		'base_uri'	=> $config['api']['base_url'],
		'token'		=> $config['api']['token']
	]);
});

// Define routes
require_once APPLICATION_PATH . '/app/routes/home.php';
require_once APPLICATION_PATH . '/app/routes/product/add.php';
require_once APPLICATION_PATH . '/app/routes/product/edit.php';
require_once APPLICATION_PATH . '/app/routes/product/save.php';
require_once APPLICATION_PATH . '/app/routes/product/update.php';
require_once APPLICATION_PATH . '/app/routes/product/delete.php';

// Kick but!
$app->run();
