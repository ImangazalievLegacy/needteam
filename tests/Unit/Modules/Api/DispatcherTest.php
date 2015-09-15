<?php

namespace Tests\Unit\Modules\Api;

use Tests\TestCase;
use Mockery;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Api\Dispatcher;
use Modules\Api\Formatter;
use Modules\Api\Serializer;

class DispatcherTest extends TestCase
{
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = $this->app->make('config');
    }

    public function testHandleException()
    {
        $responseMock = Mockery::mock('Illuminate\Http\Response');
        $responseMock->shouldReceive('getOriginalContent')->once()->andThrow('RuntimeException', 'Error');
        $responseMock->shouldReceive('status')->once()->andReturn(200);

        $headersMock = Mockery::mock('Symfony\Component\HttpFoundation\ResponseHeaderBag');
        $headersMock->shouldReceive('all')->once()->andReturn([]);

        $responseMock->headers = $headersMock;

        $request = new Request;

        $closure = function ($request) use ($responseMock) {
            return $responseMock;
        };

        $config  = $this->config->get('api');
        $formats = $config['formats'];
        $mimes   = $config['mimes'];

        $defaultAdapter = $config['defaultAdapter'];

        $configMock = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $configMock->shouldReceive('get')->once()->with('api')->andReturn($config);

        $formatter  = new Formatter();
        $serializer = new Serializer();

        $message = $config['debug'] ? 'Error' : "Internal Server Error.";

        $values = [
            ':status'   => 500,
            ':error-message' => $message,
        ];

        $formatted  = $formatter->setFormat($formats['error'])->format($values);
        $serialized = $serializer->serialize($formatted, $defaultAdapter);

        $formatterMock = Mockery::mock('Modules\Api\Formatter');
        $formatterMock->shouldReceive('setFormat')->once()->with($formats['error'])->andReturn($formatterMock);
        $formatterMock->shouldReceive('format')->once()->with($values)->andReturn($formatted);

        $serializerMock = Mockery::mock('Modules\Api\Serializer');
        $serializerMock->shouldReceive('serialize')->once()->with($formatted, $defaultAdapter)->andReturn($serialized);

        $appMock = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $appMock->shouldReceive('make')->once()->with('api.formatter')->andReturn($formatterMock);
        $appMock->shouldReceive('make')->once()->with('api.serializer')->andReturn($serializerMock);

        $dispatcher = new Dispatcher($appMock, $configMock);

        $response = $dispatcher->handle($request, $closure);
    }

    public function testHandle()
    {
        $responseMock = Mockery::mock('Illuminate\Http\Response');
        $responseMock->shouldReceive('getOriginalContent')->once()->andReturn('content');
        $responseMock->shouldReceive('status')->once()->andReturn(200);

        $headersMock = Mockery::mock('Symfony\Component\HttpFoundation\ResponseHeaderBag');
        $headersMock->shouldReceive('all')->once()->andReturn([]);

        $responseMock->headers = $headersMock;

        $request = new Request;

        $closure = function ($request) use ($responseMock) {
            return $responseMock;
        };

        $config  = $this->config->get('api');
        $formats = $config['formats'];
        $mimes   = $config['mimes'];

        $defaultAdapter = $config['defaultAdapter'];

        $configMock = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $configMock->shouldReceive('get')->once()->with('api')->andReturn($config);

        $formatter  = new Formatter();
        $serializer = new Serializer();

        $values = [
            ':status'   => 200,
            ':response' => 'content',
        ];

        $formatted  = $formatter->setFormat($formats['success'])->format($values);
        $serialized = $serializer->serialize($formatted, $defaultAdapter);

        $formatterMock = Mockery::mock('Modules\Api\Formatter');
        $formatterMock->shouldReceive('setFormat')->once()->with($formats['success'])->andReturn($formatterMock);
        $formatterMock->shouldReceive('format')->once()->with($values)->andReturn($formatted);

        $serializerMock = Mockery::mock('Modules\Api\Serializer');
        $serializerMock->shouldReceive('serialize')->once()->with($formatted, $defaultAdapter)->andReturn($serialized);

        $appMock = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $appMock->shouldReceive('make')->once()->with('api.formatter')->andReturn($formatterMock);
        $appMock->shouldReceive('make')->once()->with('api.serializer')->andReturn($serializerMock);

        $dispatcher = new Dispatcher($appMock, $configMock);

        $response = $dispatcher->handle($request, $closure);

        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($serialized, $response->content());
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
