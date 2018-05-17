<?php

/*
 * Home route
 */

use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Exception\CommandServerException;

$app->get('/', function() use($app) {

	$pageTitle = 'Välkommen!';

	$products = [];

	try
	{
		$result = $app->apiClient->GetProducts();
		$products = $result->toArray()['items'];
	}
	catch( CommandClientException $e )
	{
		$response = json_decode( $e->getResponse()->getBody() );
		$app->flashNow( 'notice', $response->message );
	}
	catch( CommandServerException $e )
	{
		$response = json_decode( $e->getResponse()->getBody() );
		$app->flashNow( 'alert', $response->message );
	}

	$app->render('home.twig', array(
		'page_title' => $pageTitle,
		'products' => $products
	));

})->name('home');
