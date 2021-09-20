<?php   

namespace App\Repository\Eloquent;   

use App\Repository\EloquentRepositoryInterface; 
use Illuminate\Database\Eloquent\Model;   
use Illuminate\Database\Eloquent\Collection;

class BaseRepository implements EloquentRepositoryInterface 
{     
    /**      
     * @var Model      
     */     
     protected $model;       

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */     
    public function __construct(Model $model)     
    {         
        $this->model = $model;
    }
 
    /**
    * @param array $attributes
    * @return Model
    */
    public function get(array $attributes = []): Collection
    {
        if ($attributes) {
            return $this->model->get($attributes);
        } else {
            return $this->model->get();
        }
    }   

    /**
    * @param array $payload
    * @return Model
    */
    public function create(array $payload): ?Model
    {
        return $this->model->create($payload);
    }   
    
    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }   

    /**
    * @param $id
    * @return Model
    */
    public function destroy($id): int
    {
        return $this->model->destroy($id);
    }     
    
   /**
    * @param $id
    * @param array $payload    
    * @return int
    */
    public function update($id, array $payload): bool 
    {
        return $this->model
            ->where('id', $id)
            ->update($payload);        
    }          
}