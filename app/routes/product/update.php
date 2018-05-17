<?php

/*
 * Product update route
 */

use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Exception\CommandServerException;

$app->put('/product/update/:productSku', function($productSku) use ($app) {

	$pageTitle = 'Produkten updaterades!';

	$params = $app->request->post();

	$todo 	= $params['todo'];

	// $sku 	= $params['sku'];
	$name 	= $params['name'];
	$price 	= (int) $params['price'];

	// Product update model(ish)
	$productModel = [
		'name' => $name,
		'price'	=> $price
	];

	try
	{
		$result = $app->apiClient->UpdateProduct( ['sku' => $productSku, 'product' => $productModel] );
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

	if($result instanceof GuzzleHttp\Command\Result)
	{
		$app->flashNow( 'notice', 'SKU: ' . $productSku );
	}
	else
	{
		$app->flashNow( 'error', 'Något gick fel när produkten skulle updateras. Prova gärna igen senare.' );
	}

	$app->render('response.twig', array(
		'page_title' => $pageTitle
	));

})->name('product.update');
