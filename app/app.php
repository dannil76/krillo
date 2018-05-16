<?php

require_once APPLICATION_PATH . '/app/bootstrap.php';

use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Exception\CommandServerException;

$app->get('/(:sku)', function($sku = null) use ($app) {

	$data = [];
	$pageTitle = 'Skapa/Redigera produkt';

	if($sku)
	{
		try
		{
			$query = [
				'sku' => $sku,
				'fields' => 'id,sku,name,price'
			];

			$result = $app->apiClient->GetProduct($query);
			$data = $result->toArray();

			$data && $pageTitle = 'Redigera produkt';
		}
		catch( CommandClientException $e )
		{
			$data['id'] = 0;
			$data['sku'] = $sku;
			$data['action'] = 'create';

			$response = json_decode( $e->getResponse()->getBody(), true );
			$message = null;
			array_key_exists('message', $response) && $message = $response['message'] . ' (sku). Vill du skapa den?';
			$app->flashNow('notice', $message);
		}
		catch( CommandServerException $e )
		{
			$response = json_decode( $e->getResponse()->getBody(), true );
			$app->flashNow('alert', $response['message']);
		}
	}

	$app->render('products_form.twig', array(
		'page_title' => $pageTitle,
		'product' => $data
	));
});


$app->post('/', function() use ($app) {

	$params = $app->request->post();

	$sku 	= $params['sku'];
	$name 	= $params['name'];
	$price 	= (int) $params['price'];

	$data = [
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

	$response = null;

	try
	{
		$result = $app->apiClient->CreateProduct( $data );
	}
	catch( CommandClientException $e )
	{
		$response = json_decode( $e->getResponse()->getBody(), true );
	}

	if($response)
	{
		$app->flash('error', $response['message']);
	}
	else
	{
		$app->flash('notice', 'Produkt sparad');
	}

	$app->redirect('/' . $sku);
});


$app->delete('/', function() use ($app) {

	$params = $app->request->post();
	$sku = (string) $params['sku'];

	$result = $app->apiClient->DeleteProduct( ['sku' => $sku] );
	
	if( (int) $result['status'] === 200 )
	{
		$app->flash('notice', 'Produkt med sku: ' . $sku . ' raderad!');
	}
	else
	{
		$app->flash('error', 'Något gick fel när produkten skulle tas bort. Prova gärna igen senare');
	}

	$app->redirect('/');
});

$app->run();
