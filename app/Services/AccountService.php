<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests;
use App\Events\UserRegistered;

class AccountService implements AccountServiceInterface
{
    /**
     * Constructor
     * 
     * @param  UserRepositoryInterface $userRepository
     * @return void
     */
	public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param  array $data
     * @return bool
     */
	public function register($data)
    {
        $activationCode = str_random(32);
        $data['hash']   = $activationCode;

        $user = $this->userRepository->register($data);

        if ($user === false) {
        	return false;
        }

        event(new UserRegistered($user));

        return true;
    }
}
