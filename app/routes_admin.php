<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Str;

require 'models/Place.php';

$app->get('/admin', function (Request $request, Response $response, $args) use ($app) {
    $loggedUser = $app->getContainer()->sentinel->check();

    if (!$loggedUser) {
        echo 'You need to be logged in to access this page.';

        return;
    }

    if (!$loggedUser->hasAccess('user.*')) {
        echo "You don't have the permission to access this page.";

        return;
    }

    // echo 'Welcome to the admin page.';
    $app->getContainer()->view->render($response, 'admin/index.twig');
});

$app->get('/admin/users', function (Request $request, Response $response, $args) use ($app) {
    $loggedUser = $app->getContainer()->sentinel->check();

    if (!$loggedUser) {
        echo 'You need to be logged in to access this page.';

        return;
    }

    if (!$loggedUser->hasAccess('user.*')) {
        echo "You don't have the permission to access this page.";

        return;
    }

    $entries = EloquentUser::all();

    // echo 'Welcome to the admin page.';
    $app->getContainer()->view->render($response, 'admin/users.twig', [
        'entries' => $entries
    ]);
});

$app->get('/admin/places', function (Request $request, Response $response, $args) use ($app) {
    // $session = new \RKA\Session();
    // $user = $session->get('user');
    $name = $request->getAttribute('csrf_name');
    $value = $request->getAttribute('csrf_value');

    $entries = Place::all();
    // $countries = Country::all();

    // $this->view->offsetSet('user', $user );
    // $this->view->offsetSet('loggedIn', ! empty($user) );
    $this->view->render($response, 'admin/places.twig', [
        'entries'   => $entries,
        'csrfName' => $name,
        'csrfValue' => $value

        // 'countries' => $countries
    ]);
    return $response;
} );

$app->post('/admin/places', function (Request $request, Response $response, $args) use ($app) {
    $attributes = $request->getParsedBody();
    $attributes['slug'] = Str::slug($attributes['name']);
    // $country = Country::find($attributes['country_id']);
    try {
        $place = Place::create($attributes);
        // $place->country()->associate($country);
        // $place->save();
    } catch( \Exception $e ) {
        die( $e->getMessage() . " " . $e->getCode() );
    }
    $uri = $request->getUri();
    return $response = $response->withRedirect($uri); //, 403);
} );