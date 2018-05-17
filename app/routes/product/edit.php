<?php

/*
 * Product edit route
 */

use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Exception\CommandServerException;

$app->get('/product/edit/:productSku', function($productSku = null) use ($app) {

	$pageTitle = 'Redigera SKU: ' . $productSku;

	$product = [];

	if($productSku)
	{
		try
		{
			$query = [
				'sku' => $productSku,
				'fields' => 'id,sku,name,price'
			];

			$result = $app->apiClient->GetProduct($query);
			$product = $result->toArray();
		}
		catch( CommandClientException $e )
		{
			$response = json_decode( $e->getResponse()->getBody() );
			$app->flashNow( 'notice', $response->message );
			$pageTitle = 'Hoppsan...';
		}
		catch( CommandServerException $e )
		{
			$response = json_decode( $e->getResponse()->getBody(), true );
			$app->flashNow( 'alert', $response['message'] );
		}
	}

	$app->render('products_add_edit.twig', array(
		'page_title' => $pageTitle,
		'product' => $product,
		'todo' => 'edit'
	));

})->name('product.edit');
