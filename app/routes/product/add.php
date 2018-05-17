<?php 

/*
 * Product add route
 */

$app->get('/product/add', function() use ($app) {

	$pageTitle = 'Lägg till produkt';

	$app->render('products_add_edit.twig', array(
		'page_title' => $pageTitle,
		'todo' => 'add'
	));

})->name('product.add');
