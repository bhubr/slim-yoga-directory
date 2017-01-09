<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/search', function (Request $request, Response $response, $args) use ($app) {
    // CSRF token name and value
    $name = $request->getAttribute('csrf_name');
    $value = $request->getAttribute('csrf_value');

    // $component1 = new Component([
    //     'id'   => 'roberto-div',
    //     'name' => 'Roberto',
    //     'age'  => 50
    // ]);

    // $component2 = new Component([
    //     'id'   => 'julia-div',
    //     'name' => 'Julia',
    //     'age'  => 34
    // ]);

    $component3 = new InputComponent([
        'type' => 'InputComponent',
        'inputId'   => 'search-cities-2',
        'listId'   => 'list-cities-2',
        'placeholder'   => 'I am input #search-cities-2',
        'renderInput' => false,
        'tagWrap' => 'div',
        'multi' => true,
        'template' => 'prout'
    ]);

    $components = TwigComponents::getInstance();
    // $components->register( 'c1', $component1 );
    // $components->register( 'c2', $component2 );
    $components->register( 'c3', $component3 );

    $app->getContainer()->view->render($response, 'search.html.twig', [
        'csrfName' => $name,
        'csrfValue' => $value,
        'ts' => time(),
        'hbsTemplate' => '  {{#each items}}
        <div class="row">
          <div class="col-md-5">
            <h4>{{ name }}</h4>
            <p>Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer.</p>
          </div>
          <div class="col-md-7">{{{ map_embed }}}</div>
        </div>
  {{/each}}
'
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
        'styles' => 'Style',
        'places' => 'Place'
    ];
    if( ! array_key_exists($searchWhat, $validTypes) ) {
        return $response->write('Not Found')->withStatus(404);
    }
    $typeClass = $validTypes[$searchWhat];

    $searchTerms = $request->getParams();
    if (isset($searchTerms['fields'])) {
        unset($searchTerms['fields']);
    }

    $query = $typeClass;
    foreach( $searchTerms as $key => $val ) {
        if(empty($val)) continue;
        if( preg_match('/.*_?id/', $key) ) {
            $query = is_string( $query ) ? $query::where( $key, '=', (int)$val ) :
                $query->where( $key, '=', (int)$val );
        }
        else {
            $query = is_string( $query ) ? $query::where( $key, 'LIKE', "{$val}%" ) :
                $query->where( $key, 'LIKE', "%{$val}%" );
        }
    }
    try {
        $searchResults = is_string( $query ) ? $query::limit(10)->get() :
            $query->limit(10)->get();
    } catch( \Exception $e ) {
        if( is_a($e, 'Illuminate\Database\QueryException') && $e->getCode() === '42S22' ) {
            $error = "Queried object {$typeClass}: invalid attribute";
            $pattern = '/.*Unknown column \'(.*)\' in.*/';
            preg_match( $pattern, $e->getMessage(), $matches );
            if( count( $matches ) === 2 ) $error .= " '" . $matches[1] . "'";
        }
        else {
            $error = $e->getCode() . ':' . $e->getMessage();
        }
        return $response->write( $error )->withStatus( 400 );
    }
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

$app->get('/search-places', function(Request $request, Response $response, $args) {
    $params = $request->getParams();
    $places = Place::where('city_id', '=', $params['city_id'] )->limit(10)->get();
    return $response->withJson(['items' =>$places]);
});