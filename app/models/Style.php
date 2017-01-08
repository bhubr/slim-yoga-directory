<?php

use Illuminate\Database\Eloquent\Model;

class Style extends Model {
  protected $fillable = ['name', 'slug'];

  // public function schools() {
  //   return $this->belongsToMany('School');
  // }
}
