<?php

namespace Modules\Api\Serializer\Adapter;

class PhpSerialize implements AdapterInterface
{
	/**
     * @param  mixed $value data to serialize
     * @return string
     */
    public function serialize($value)
    {
        return serialize($value);
    }
}
