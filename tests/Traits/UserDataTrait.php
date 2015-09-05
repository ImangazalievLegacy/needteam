<?php

namespace Tests\Traits;

use Auth;
use App\Models\User;

trait UserDataTrait {

    public function getUserData()
    {
        return [
            'username' => 'User',
            'email'    => 'user@domain.tld',
            'password' => 'password',
        ];
    }

    public function createAccount()
    {
        $data = [
            'username' => 'User',
            'email'    => 'user@domain.tld',
            'password' => 'password',
            'active'   => true,
            'blocked'  => false,
            'hash'     => 'activationCode'
        ];

        User::truncate();

        return User::create($data);
    }

    public function loginAs($data)
    {
        Auth::attempt($data);
    }
}