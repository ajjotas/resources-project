<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface EloquentRepositoryInterface
{
   /**
    * @param array $attributes
    * @return Model
    */
   public function get(array $attributes = []): Collection;

   /**
    * @param array $payload
    * @return Model
    */
   public function create(array $payload): ?Model;   

   /**
    * @param $id
    * @return Model
    */
   public function find($id): ?Model;       

   /**
    * @param $id
    * @return int
    */
    public function destroy($id): int;   
    
   /**
    * @param $id
    * @param array $payload    
    * @return int
    */
    public function update($id, array $payload): bool;      
}
