<?php

use Illuminate\Database\Eloquent\Model;

class Place extends Model {
  protected $fillable = ['name', 'street_address', 'city_id', 'postcode', 'country_id', 'state_id', 'phone', 'latitude', 'longitude', 'map_link', 'map_embed'];

  public function schools() {
    return $this->belongsToMany('School');
  }

  public function city() {
    return $this->belongsTo('City');
  }
}
