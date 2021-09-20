<?php

namespace App\Repository\Eloquent;

use App\Link;
use App\Repository\LinkRepositoryInterface;

class LinkRepository extends BaseRepository implements LinkRepositoryInterface
{

   /**
    * LinkRepository constructor.
    *
    * @param Link $model
    */
   public function __construct(Link $model)
   {
       parent::__construct($model);
   }

}