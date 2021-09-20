<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Html extends Model
{
    protected $table = 'html';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id', 'description', 'snippet'];
}
