<?php 

/*
 * Product save route
 */

use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Exception\CommandServerException;

$app->post('/product/save', function() use ($app) {

	$pageTitle = 'Produkten sparades!';

	$params = $app->request->post();

	$todo 	= $params['todo'];

	$sku 	= $params['sku'];
	$name 	= $params['name'];
	$price 	= (int) $params['price'];

	// Product add new model(ish)
	$productModel = [
		'product' => [
			'sku' => $sku,
			'name' => $name,
			'attribute_set_id' => 4,
			'price'	=> $price,
			'type_id' => 'simple',
			'extension_attributes' => [
				'category_links' => [
					[
						'position' => 1,
						'category_id' => "3"
					]
				],
				'stock_item' => [
					'qty' => "10",
					'is_in_stock' => true
				]
			]
		]
	];

	try
	{
		$result = $app->apiClient->CreateProduct( $productModel );
	}
	catch( CommandClientException $e )
	{
		$response = json_decode( $e->getResponse()->getBody(), true );
		$app->flashNow( 'error', $response['message'] );
		$result = false;
	}
	catch( CommandServerException $e )
	{
		$response = json_decode( $e->getResponse()->getBody(), true );
		$app->flashNow( 'alert', $response['message'] );
		$result = false;
	}

	if($result instanceof GuzzleHttp\Command\Result)
	{
		$app->flashNow( 'notice', 'SKU: ' . $sku );
	}
	else
	{
		$app->flashNow( 'notice', 'Något gick fel när produkten skulle sparas.' );
		$pageTitle = 'Hoppsan...';
	}

	$app->render('response.twig', array(
		'page_title' => $pageTitle
	));

})->name('product.save');
