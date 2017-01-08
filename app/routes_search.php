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

$searchClosure = function (Request $request, Response $response, $args) use ($app) {
    $route = $request->getAttribute('route');
    $searchWhat = $route->getArgument('what');
    $fields = $request->getParam('fields');
    $fieldsArr = explode( ',', $fields );

    $fields = empty($fields) ? ['id', 'name'] :
        array_merge(['id'], $fieldsArr);

    $validTypes = [
        'cities' => 'City',
        'styles' => 'Style'
    ];
    if( ! array_key_exists($searchWhat, $validTypes) ) {
        return $response->write('Not Found')->withStatus(404);
    }
    $typeClass = $validTypes[$searchWhat];

    $searchTerm = $request->getParam('s');
    $searchResults = $typeClass::where('name', 'LIKE', "{$searchTerm}%")->limit(10)->get();
    $mappedResults = $searchResults->map( function( $item ) use($fields) {
        $mapped = [
            'id' => $item->id
        ];
        foreach( $fields as $f ) {
            $mapped[$f] = $item->$f;
        }
        return $mapped;
    } );
    $data = [
        'items' => $mappedResults
    ];
    return $response->withJson($data);
};

$app->get('/search/{what}', $searchClosure);