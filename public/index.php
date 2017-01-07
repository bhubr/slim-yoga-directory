<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'debug'         => true,
        'whoops.editor' => 'sublime' // Support click to open editor
    ]
]);

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

//register bindings
include_once __DIR__.'/../app/bootstrap/container.php';

include_once __DIR__.'/../app/routes_auth.php';
include_once __DIR__.'/../app/routes_setup.php';
include_once __DIR__.'/../app/routes_admin.php';


$app->add(new \Slim\Csrf\Guard);

$app->add( function( $request, $response, $next ) {
    session_start();
    return $next( $request, $response );
});

$app->run();
