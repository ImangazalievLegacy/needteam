<?php

namespace Modules\Api\Serializer\Adapter;

interface AdapterInterface
{
    /**
     * @param  mixed $value data to serialize
     * @return string
     */
    public function serialize($value);
}
