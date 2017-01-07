<?php

use Illuminate\Database\Eloquent\Model;

class Place extends Model {
  protected $fillable = ['name', 'street_address', 'city', 'postcode', 'country_id', 'state_id', 'phone', 'latitude', 'longitude', 'map_link', 'map_embed'];  
}
