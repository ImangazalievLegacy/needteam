<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use Mockery;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Listeners\SendActivationCode;
use Tests\Traits\UserDataTrait;
use App\Events\UserRegistered;
use App\Models\User;

class SendActivationCodeTest extends TestCase
{
    use UserDataTrait;

    public function setUp()
    {
        parent::setUp();

        \Artisan::call('migrate', array('--force' => true));
    }

    public function testHandle()
    {
    	$mailer = Mockery::mock('Illuminate\Contracts\Mail\Mailer');
        $user   = $this->createAccount();
    	$event  = new UserRegistered($user);

        $mailer->shouldReceive('send')->once();

    	$listener = new SendActivationCode($mailer);
    	$listener->handle($event);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
