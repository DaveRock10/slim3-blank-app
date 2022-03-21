<?php 

	session_start();

	require __DIR__ . '/../vendor/autoload.php';	

	$app = new \Slim\App([

		'settings' => [
			'displayErrorDetails' => true,

			/*use yoyr DB settings*/
			'db'=>[
				'driver' => '',
				'host' => '',
				'database' => '',
				'username' => '',
				'password' => '',
				'charset' => '',
				'collation' => '',
				'prefix' => ''
			]
		]

	]);


	require __DIR__ . '/../app/routes.php';

	$container = $app->getContainer();

	$capsule = new \Illuminate\Database\Capsule\Manager;

	$capsule->addConnection($container['settings']['db']);

	$capsule->setAsGlobal();

	$capsule->bootEloquent();

	$container['db'] = function($container) use ($capsule){

		return $capsule;

	};


	$container['view'] = function($container){

		$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views',[

			'cache' => false


		]);

		$view->addExtension(new \Slim\Views\TwigExtension(

			$container->router,
			$container->request->getUri()

		));

		return $view;

	};

	$container['HomeController'] = function($container){

		return new \App\Controllers\HomeController($container);

	};
