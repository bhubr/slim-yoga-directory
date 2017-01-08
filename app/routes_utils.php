<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->get('/import-villes', function (Request $request, Response $response, $args) use ($app) {
    $fh = fopen('../villes_france_head.csv', 'r');
    // var_dump($fh);die();

    $header = fgetcsv($fh);

    // for( $i = 0 ; $i < 5 ; $i++ ) {
    while (($row = fgetcsv($fh)) !== FALSE) {
      // $row = fgetcsv($fh);
      // var_dump($row); die();
      $row = array_combine($header, $row);
      // var_dump($row);
      // $attrs = [
      //   'name', 'slug', 'postcode', 'country_id', 'country_id', 'state_id', 'district', 'latitude', 'longitude'
      // ];
      $city = City::create([
        'name' => $row['name'],
        'slug' => $row['slug'],
        'postcode' => substr($row['postcode'], 0, 5),
        'country_id' => 1,
        'state_id' => (int)$row['dept'],
        'district' => $row['district'],
        'latitude' => $row['lat_deg'],
        'longitude' => $row['lon_deg'],
      ]);
    }
});