<?php
namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface 
{
    public function getOneById($id): ?Model;

    /** @return Collection|array<Model> */
    public function getByIds(array $ids,string $order , string $sort): Collection;

    /** @return Collection|array<Model> */
    public function getAll(): Collection;

    /** @return Model */
    public function saveData(array $data,bool $saveMultipleAttribute);

    public function updateData(array $data);


}