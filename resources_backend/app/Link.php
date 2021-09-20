<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'link';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id', 'description', 'link', 'newTab'];
}
