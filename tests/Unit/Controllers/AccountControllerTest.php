<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Mockery;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\Traits\UserDataTrait;
use Auth;

class AccountControllerTest extends TestCase
{
    use UserDataTrait;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->withoutMiddleware();

        $this->accountService = Mockery::mock('App\Services\AccountServiceInterface');
        $this->app->instance('App\Services\AccountServiceInterface', $this->accountService);
    }

    public function testPostCreate()
    {
        \Artisan::call('migrate', array('--force' => true));

        //валидация не прошла
    	$this->action('POST', 'AccountController@postCreate');
    	$this->assertResponseStatus(302);
    	$this->assertSessionHasErrors();

        $data = $this->getUserData();

        //ошибка при регистрации
        $this->accountService->shouldReceive('register')->once()->andReturn(false);
        $this->app->instance('App\Services\AccountServiceInterface', $this->accountService);

        $this->action('POST', 'AccountController@postCreate', $data);
    	$this->assertRedirectedToRoute('home');

        //пользователь успешно зарегистрирован
        $this->accountService->shouldReceive('register')->once()->andReturn(true);
        $this->app->instance('App\Services\AccountServiceInterface', $this->accountService);

        $this->action('POST', 'AccountController@postCreate', $data);
        $this->assertRedirectedToRoute('home');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
