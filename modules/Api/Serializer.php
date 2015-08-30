<?php

namespace Modules\Api;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Modules\Api\Serializer\Adapter\AdapterInterface;
use InvalidArgumentException;

class Serializer
{
    protected $adapters = [
        'json' => 'Modules\Api\Serializer\Adapter\Json',
        'php-serialize' => 'Modules\Api\Serializer\Adapter\PhpSerialize',
    ];

    protected $defaultAdapter;

    public function __construct($defaultAdapter = 'json')
    {
        $this->setDefaultAdapter($defaultAdapter);
    }

    public function serialize($value, $adapterName = null)
    {
        if ($adapterName !== null) {
            $adapter = $this->getAdapter($adapterName);
        } else {
            $adapter = $this->getDefaultAdapter();
        }

        $serialized = $adapter->serialize($value);

        return $serialized;
    }

    /**
     * @return string
     */
    public function getDefaultAdapterName()
    {
        return $this->defaultAdapter;
    }

    /**
     * @return string
     */
    public function getDefaultAdapter()
    {
        return $this->getAdapter($this->defaultAdapter);
    }

    /**
     * @param  string $defaultAdapter
     * @return $this
     * @throws \RuntimeException
     */
    public function setDefaultAdapter($name)
    {
    	if (!$this->hasAdapter($name)) {
            throw new RuntimeException(sprintf('Adapter "%s" not found.', $name));
        }

        $this->defaultAdapter = $name;
    
        return $this;
    }

    /**
     * @param  string $name
     * @param  string $class
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addAdapter($name, $class)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException(sprintf('%s expects parameter 1 to be string, %s given', __METHOD__, gettype($name)));
        }

        if (!$class instanceof AdapterInterface) {
            throw new InvalidArgumentException(sprintf('%s expects parameter 2 to be string, %s given', __METHOD__, gettype($class)));
        }

        $this->adapters[$name] = $class;

        return $this;
    }

    /**
     * @param  string $name
     * @return Modules\Api\Serializer\Adapter\AdapterInterface
     * @throws \RuntimeException
     */
    public function getAdapter($name)
    {
        if (!$this->hasAdapter($name)) {
            throw new RuntimeException(sprintf('Adapter "%s" not found.', $name));
        }

        return new $this->adapters[$name];
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function hasAdapter($name)
    {
        return array_key_exists($name, $this->adapters);
    }
}
