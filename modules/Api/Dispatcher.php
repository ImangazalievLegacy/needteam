<?php

namespace Modules\Api;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Exception;
use RuntimeException;

class Dispatcher
{
    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $formats;

    /**
     * @var array
     */
    protected $mimes;

    /**
     * @var string
     */
    protected $defaultAdapter;

    /**
     * Constructor.
     * 
     * @param  Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app, Config $config)
    {
        $this->formatter  = $app->make('api.formatter');
        $this->serializer = $app->make('api.serializer');

        $this->defaultAdapter = $config->get('api.defaultAdapter');
        $this->formats        = $config->get('api.formats');
        $this->mimes          = $config->get('api.mimes');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \RuntimeException
     */
    public function handle($request, Closure $next)
    {
        $headers = array();

        try {
            // the response received from the controller
            $response = $next($request);

            $statusCode = $response->status();
            $headers    = $response->headers->all();

            // if this redirection
            $content = ($statusCode == 302) ? ['redirect_url' => $response->getTargetUrl()] : $response->getOriginalContent();

            if (is_object($content)) {
                if ($content instanceof Arrayable) {
                    $content = $content->toArray();
                } else {
                    throw new RuntimeException('The returned object must implement Illuminate\Contracts\Support\Arrayable interface');
                }
            }

            $format = $this->getFormat('success');

            $values = [
                ':status'   => $statusCode,
                ':response' => $content,
            ];

        } catch (HttpExceptionInterface $e) {

            $format = $this->getFormat('error');

            $statusCode = $e->getStatusCode();

            $values = [
                ':status'        => $statusCode,
                ':error-message' => $e->getMessage(),
            ];

        } catch (Exception $e) {

            $format = $this->getFormat('error');

            $statusCode = 500;

            $values = [
                ':status'  => $statusCode,
                ':error-message' => "Internal Server Error.",
            ];
        }

        $defaultAdapter = $this->defaultAdapter;
        $mime           = array_key_exists($defaultAdapter, $this->mimes) ? $this->mimes[$defaultAdapter] : 'text/plain';

        $content = $this->formatter->setFormat($format)->format($values);
        $content = $this->serializer->serialize($content, $defaultAdapter);

        $headers['content-type'] = $mime;

        $response = new Response($content, $statusCode, $headers);

        return $response;
    }

    protected function getFormat($name)
    {
        return $this->formats[$name];
    }
}
