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

    public function testGetActivate()
    {
        //ошибка при активации
        $this->accountService->shouldReceive('activate')->once()->andReturn(false);
        $this->app->instance('App\Services\AccountServiceInterface', $this->accountService);
        
        $this->action('GET', 'AccountController@getActivate', ['activationCode']);
        $this->assertRedirectedToRoute('home');

        //аккаунт успешно активирован
        $this->accountService->shouldReceive('activate')->once()->andReturn(true);
        $this->app->instance('App\Services\AccountServiceInterface', $this->accountService);
        
        $this->action('GET', 'AccountController@getActivate', ['activationCode']);
        $this->assertRedirectedToRoute('home');
    }

    public function testPostLogin()
    {
        $data = $this->getUserData();

        //ошибка при авторизации
        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $this->action('POST', 'AccountController@postLogin', $data);
        $this->assertResponseStatus(302);

        //авторизация прошла успешно
        Auth::shouldReceive('attempt')->once()->andReturn(true);

        $this->action('POST', 'AccountController@postLogin', $data);
        $this->assertResponseStatus(302);
    }

    public function testLogout()
    {
        Auth::shouldReceive('logout')->once();

        $this->action('GET', 'AccountController@getLogout');
        $this->assertRedirectedToRoute('home');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
