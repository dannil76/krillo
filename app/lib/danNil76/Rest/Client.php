<?php

namespace danNil76\Rest;

use GuzzleHttp\Client as GClient;
use GuzzleHttp\Command\Guzzle\Description as GDescription;
use GuzzleHttp\Command\Guzzle\GuzzleClient as GGuzzClient;

class Client extends GGuzzClient
{
	public static function create( $config = [] )
	{
		$serviceJson = json_decode( file_get_contents( $config['service'] ), true );

		$serviceDesc = new GDescription(
			['baseUrl' => $config['base_uri']] + (array) $serviceJson
		);

		$client = new GClient([
			'headers' => [
				'Authorization'		=> 'Bearer ' . $config['token'],
				'Content-type' 		=> 'application/json',
				'Accept'			=> 'application/json'
			]
		]);

		return new static( $client, $serviceDesc, NULL, NULL, NULL, $config );
	}
}