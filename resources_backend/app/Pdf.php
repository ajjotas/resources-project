<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $table = 'pdf';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id', 'description', 'path'];
}
