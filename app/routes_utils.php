<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->get('/import-villes', function (Request $request, Response $response, $args) use ($app) {
    $fh = fopen('../villes_france_head.csv', 'r');
    $header = fgetcsv($fh);

    while (($row = fgetcsv($fh)) !== FALSE) {
      $row = array_combine($header, $row);
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