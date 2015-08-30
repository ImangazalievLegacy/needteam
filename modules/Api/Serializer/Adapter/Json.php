<?php

namespace Modules\Api\Serializer\Adapter;

class Json implements AdapterInterface
{
	/**
     * @param  mixed $value data to serialize
     * @return string
     */
    public function serialize($value)
    {
        return json_encode($value);
    }
}
