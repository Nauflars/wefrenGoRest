<?php

namespace App\Users\Domain\Repository;

use App\Users\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function find(int $id): User;

    public function save(User $user): User;

    public function findAll(int $page): array;
}
