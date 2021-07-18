<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
* Interface RepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
{
    public function find(int $id): Model;
    
    public function create(array $data): Model;

    public function update(array $data): Model;

    public function delete(int $id): bool;

    public function all(): Collection;
}