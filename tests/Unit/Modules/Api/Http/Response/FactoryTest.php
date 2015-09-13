<?php

namespace Tests\Unit\Modules\Api\Http\Response;

use Tests\TestCase;
use Mockery;

use Modules\Api\Http\Response\Factory;

class FactoryTest extends TestCase
{
    public function testCreated()
    {
        $factory = new Factory();
        $response = $factory->created('http://example.com');

        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(201, $response->status());
        $this->assertEquals(null, $response->content());
        $this->assertEquals('http://example.com', $response->headers->get('Location'));
    }

    public function testAccepted()
    {
        $factory = new Factory();
        $response = $factory->accepted('http://example.com', 'content');

        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(202, $response->status());
        $this->assertEquals('content', $response->content());
        $this->assertEquals('http://example.com', $response->headers->get('Location'));
    }

    public function testNoContent()
    {
        $factory = new Factory();
        $response = $factory->noContent();

        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(204, $response->status());
        $this->assertEquals(null, $response->content());
    }

    public function testError()
    {
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'test');

        $response = new Factory();
        $response->error('test', 200);
    }

    public function testBadRequest()
    {
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'Bad Request');

        $response = new Factory();
        $response->errorBadRequest('Bad Request');
    }

    public function testForbidden()
    {
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'Forbidden');

        $response = new Factory();
        $response->errorForbidden('Forbidden');
    }

    public function testInternal()
    {
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'Internal Error');

        $response = new Factory();
        $response->errorInternal('Internal Error');
    }

    public function testUnauthorized()
    {
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'Unauthorized');

        $response = new Factory();
        $response->errorUnauthorized('Unauthorized');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
