<?php

namespace App\Repository\Eloquent;

use App\Pdf;
use App\Repository\PdfRepositoryInterface;

class PdfRepository extends BaseRepository implements PdfRepositoryInterface
{

   /**
    * PdfRepository constructor.
    *
    * @param Pdf $model
    */
   public function __construct(Pdf $model)
   {
       parent::__construct($model);
   }

}