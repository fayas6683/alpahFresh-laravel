<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(

		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => 'B3BSXYS_9Mvam8yCMRloBg',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => 'sk_test_2Lc7FtAroUkoK1YBKscWUT8Z',
	),

	'braintree' => array(
		'cse' => 'MIIBCgKCAQEA0EsGDIvXf7pL1GpQnZ4hjpnGw5ECAkY5Vn787f7ptARkA8Q0wygNB9rvZ8aSkMLEB9bizF6zNEDJOTYe1g0/8u4hcBeXRnnOcxKWSAOkzqB4aMRX2WER10VH9gqeEYeI69gOGVt3duzP/SqklUfeTtUX765qI15mx1ru9kRIcDEDp3j1ajZ3dnfvkMakhd054P8PKSyqWrkHQCMBN50FXJHwO431xGkTCwyhrjSbooWFPEGO5idfcB70F5Wowt9H+3bTFz7JN3asBIf2o8WwXHmHlBa+fFTw7HtV3gs032Np7siyeEzKaVa7NzAKIO/ZLct9tCujbyd7jeaT7Q253QIDAQAB',
	),


);
