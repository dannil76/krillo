<?php

require_once APPLICATION_PATH . '/app/bootstrap.php';

use GuzzleHttp\Command\Exception\CommandClientException;

// use Gilbitron\Util\SimpleCache;

$app->get('/(:sku)', function($sku = null) use ($app) {

	$data = [];
	$pageTitle = 'Skapa ny produkt';

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
			$data['sku'] = $sku;

			$error = json_decode( $e->getResponse()->getBody(), true );
			$errorMessage = null;
			array_key_exists('message', $error) && $errorMessage = $error['message'] . '. Vill du skapa den?';

			$app->flashNow('error', $errorMessage);
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

	// dbug(json_encode( $data, JSON_PRETTY_PRINT ));

	$error = null;

	try
	{
		$result = $app->apiClient->CreateProduct( $data );
		dbug($result);
	}
	catch( CommandClientException $e )
	{
		dbug($e->getMessage());
		$error = json_decode( $e->getResponse()->getBody(), true );
		// dbug($error);
	}

	$error && $app->flash('error', $error);

	$app->flash('notice', 'Produkt sparad');

	$app->redirect('/' . $sku);
});

$app->run();
