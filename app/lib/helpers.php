<?php

function getToken()
{
	return exec('../misc/get_token.sh');
}

function dbug($data)
{
	echo '<pre style="background:black;font-size: 12px; color:cyan;padding:20px;">';
	print_r($data);
	echo "</pre>";
}
