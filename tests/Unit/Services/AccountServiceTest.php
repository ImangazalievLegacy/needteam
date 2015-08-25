<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\Traits\UserDataTrait;
use App\Services\AccountService;
use App\Models\User;

class AccountServiceTest extends TestCase
{
    use UserDataTrait;
    
    public function testRegister()
    {
        $data = $this->getUserData();

    	$userRepository = Mockery::mock('App\Repositories\User\UserRepositoryInterface');
        $userRepository->shouldReceive('register')->once()->andReturn(false);
        
        $accountService = new AccountService($userRepository);
        $result = $accountService->register($data);

        $this->assertFalse($result);

        $user = new User($data);

        $userRepository = Mockery::mock('App\Repositories\User\UserRepositoryInterface');
        $userRepository->shouldReceive('register')->once()->andReturn($user);

        \Event::shouldReceive('fire')->once();

        $accountService = new AccountService($userRepository);
        $result = $accountService->register($data);

        $this->assertTrue($result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
