<?php

namespace App\Repositories\User;

use App\Models\User;

class DbUserRepository implements UserRepositoryInterface
{
    /**
     * Constructor
     * 
     * @param  \App\Models\User $users
     * @return void
     */
    public function __construct(User $users)
    {
        return $this->users = $users;
    }

    /**
     * @param  array $data
     * @return App\Models\User|bool
     */
    public function register($data)
    {
        $user = $this->users->create($data);

        return $user->save() ? $user : false;
    }

    /**
     * @param  string $code
     * @return App\Models\User|bool
     */
    public function activate($code)
    {
        $user = $this->users->where('hash', $code)->where('active', false);

        if ($user->count() == 0) {
            return false;
        }

        $user = $user->first();

        $user->active = 1;
        $user->hash   = '';

        return $user->save() ? $user : false;
    }

     /**
     * @param  int $id
     * @return App\Models\User
     */
    public function find($id)
    {
        return $this->users->find($id);
    }
}
