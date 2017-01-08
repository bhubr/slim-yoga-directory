<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/search', function (Request $request, Response $response, $args) use ($app) {
    // CSRF token name and value
    $name = $request->getAttribute('csrf_name');
    $value = $request->getAttribute('csrf_value');

    $app->getContainer()->view->render($response, 'search.html.twig', [
        'csrfName' => $name,
        'csrfValue' => $value
    ]);
    return $response;
});

$app->get('/cities', function (Request $request, Response $response, $args) use ($app) {
    $searchTerm = $request->getParam('s');
    $searchResults = City::where('name', 'LIKE', "{$searchTerm}%")->limit(10)->get();
    $mappedResults = $searchResults->map( function( $item ) {
        return [
            'id' => $item->id,
            'name' => $item->name
        ];
    } );
    $data = [
        'items' => $mappedResults
    ];
    return $response->withJson($data);
});