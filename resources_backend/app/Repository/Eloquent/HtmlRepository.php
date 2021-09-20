<?php

namespace App\Repository\Eloquent;

use App\Html;
use App\Repository\HtmlRepositoryInterface;

class HtmlRepository extends BaseRepository implements HtmlRepositoryInterface
{

   /**
    * HtmlRepository constructor.
    *
    * @param Html $model
    */
   public function __construct(Html $model)
   {
       parent::__construct($model);
   }

}