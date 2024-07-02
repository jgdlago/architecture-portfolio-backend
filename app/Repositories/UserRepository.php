<?php

namespace App\Repositories;

use App\Models\User;
use App\RepositoryInterfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected User $user;
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
