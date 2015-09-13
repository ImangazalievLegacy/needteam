<?php

namespace Modules\Api;

trait Helpers
{
    /**
     * Get the response factory instance.
     *
     * @return \Modules\Api\Http\Response\Factory
     */
    public function response()
    {
        return app('Modules\Api\Http\Response\Factory');
    }
}