<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Exception;
/**
* Interface RepositoryInterface
* @package App\Repositories
*/
abstract class EloquentRepository implements EloquentRepositoryInterface
{   
    protected $model;
    
    protected function objectName()
    {
        $namespaceArray = explode('\\', get_class($this->model));
        return end($namespaceArray);
    } 

    public function find(int $id): Model
    {
        $model = $this->model->find($id);
     
        if(!$model) throw new Exception('Not found '. $this->objectName() .' for id '.$id);
        
        $this->model = $model;

        return $this->model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function delete(int $id): bool
    {
        $this->find($id);
        return $this->model->delete();
    }

}