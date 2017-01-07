<?php

use Illuminate\Database\Eloquent\Model;

class City extends Model {
  protected $fillable = ['name', 'slug', 'postcode', 'country_id', 'state_id', 'district', 'latitude', 'longitude'];  

  // public function schools() {
  //   return $this->belongsToMany('School');
  // }
}
