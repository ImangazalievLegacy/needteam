<?php

namespace Modules\Api\Http\Response;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;

class Factory
{
    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param null|string $location
     * @return \Response
     */
    public function created($location = null)
    {
        $response = new Response(null);
        $response->setStatusCode(201);

        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param null|string $location
     * @param mixed       $content
     * @return \Response
     */
    public function accepted($location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(202);

        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @return \Response
     */
    public function noContent()
    {
        $response = new Response(null);

        return $response->setStatusCode(204);
    }

    /**
     * Return an error response.
     *
     * @param  string $message
     * @param  int    $statusCode
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param string $message
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorForbidden($message = 'Forbidden')
    {
        $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->error($message, 401);
    }
}