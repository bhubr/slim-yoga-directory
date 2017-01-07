<?php

use Illuminate\Database\Eloquent\Model;

class School extends Model {
  protected $fillable = ['name', 'slug'];

  public function places() {
    return $this->belongsToMany('Place');
  }
}
