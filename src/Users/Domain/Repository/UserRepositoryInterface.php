<?php

namespace App\Users\Domain\Repository;

use App\Users\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function find($id);

    public function findAll();

    public function save(User $user): User;
}
