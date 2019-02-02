<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 2019-01-12
 * Time: 12:54
 */

namespace App\Services\User;

use App\Entity\User;

interface UserManagerInterface
{
    public function findAll(): array;
    public function save(User $user): User;
    public function remove(User $user): void;
}
