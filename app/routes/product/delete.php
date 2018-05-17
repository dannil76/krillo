<?php

/*
 * Product delete route
 */

use GuzzleHttp\Command\Exception\CommandClientException;

$app->delete('/product/delete/:productSku', function($productSku) use ($app) {

	$pageTitle = 'Produkten raderades!';

	try
	{
		$result = $app->apiClient->DeleteProduct( ['sku' => $productSku] );
	}
	catch( CommandClientException $e )
	{
		$response = json_decode( $e->getResponse()->getBody(), true );
		$app->flashNow( 'notice', $response['message'] );
	}

	if( (int) $result['status'] === 200 )
	{
		$app->flashNow( 'notice', 'SKU: ' . $productSku );
	}
	else
	{
		$app->flashNow( 'error', 'Något gick fel när produkten skulle raderas. Prova gärna igen senare.' );
	}

	$app->render('response.twig', array(
		'page_title' => $pageTitle
	));

})->name('product.delete');
