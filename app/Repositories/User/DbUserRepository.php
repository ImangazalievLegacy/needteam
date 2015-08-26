<?php

namespace App\Repositories\User;

use App\Models\User;

class DbUserRepository implements UserRepositoryInterface
{
	/**
	 * Constructor
	 * 
	 * @param  \App\Models\User $user
	 * @return void
	 */
	public function __construct(User $user)
	{
		return $this->users = $user;
	}

	/**
	 * @param  array $data
	 * @return bool|App\Models\User
	 */
	public function register($data)
	{
		$user = $this->users->create($data);

		return $user->save() ? $user : false;
	}

	/**
	 * @param  string $code
	 * @return bool|App\Models\User
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
}
