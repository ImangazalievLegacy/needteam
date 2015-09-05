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

    public function testHandle()
    {
        $this->accountService = Mockery::mock('App\Services\AccountServiceInterface');

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

        $formats = $this->getFormats();
        $mimes   = $this->getMimes();

        $configMock = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $configMock->shouldReceive('get')->once()->with('api.defaultAdapter')->andReturn('json');
        $configMock->shouldReceive('get')->once()->with('api.formats')->andReturn($formats);
        $configMock->shouldReceive('get')->once()->with('api.mimes')->andReturn($mimes);

        $formatter  = new Formatter();
        $serializer = new Serializer();

        $values = [
            ':status'   => 200,
            ':response' => 'content',
        ];

        $formatted  = $formatter->setFormat($formats['success'])->format($values);
        $serialized = $serializer->serialize($formatted, 'json');

        $formatterMock = Mockery::mock('Modules\Api\Formatter');
        $formatterMock->shouldReceive('setFormat')->once()->with($formats['success'])->andReturn($formatterMock);
        $formatterMock->shouldReceive('format')->once()->with($values)->andReturn($formatted);

        $serializerMock = Mockery::mock('Modules\Api\Serializer');
        $serializerMock->shouldReceive('serialize')->once()->with($formatted, 'json')->andReturn($serialized);

        $appMock = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $appMock->shouldReceive('make')->once()->with('api.formatter')->andReturn($formatterMock);
        $appMock->shouldReceive('make')->once()->with('api.serializer')->andReturn($serializerMock);

        $dispatcher = new Dispatcher($appMock, $configMock);

        $response = $dispatcher->handle($request, $closure);

        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($serialized, $response->content());
    }

    public function getFormats()
    {
        return $this->config->get('api.formats');
    }

    public function getMimes()
    {
        return $this->config->get('api.mimes');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
