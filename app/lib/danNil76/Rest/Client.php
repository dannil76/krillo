<?php

namespace danNil76\Rest;

use GuzzleHttp\Client as GClient;
use GuzzleHttp\Command\Guzzle\Description as GDescription;
use GuzzleHttp\Command\Guzzle\GuzzleClient as GGuzzClient;
use GuzzleHttp\Command\Exception\CommandClientException;

class Client extends GGuzzClient
{
	public static function create( $config = [] )
	{
		if( !is_file($config['service']) )
		{
			throw new \Exception('Service file not found! Looking for: ' . $config['service'], 1);
		}

		$serviceJson = json_decode( file_get_contents( $config['service'] ), true );

		$serviceDesc = new GDescription(
			$serviceJson
		);

		$client = new GClient([
			'headers' => [
				'Authorization'		=> 'Bearer ' . $config['token'],
				'Content-Type' 		=> 'application/json',
				'Accept'			=> 'application/json'
			]
		]);

		return new static( $client, $serviceDesc, NULL, NULL, NULL, $config );
	}
}
