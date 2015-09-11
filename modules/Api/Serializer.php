<?php

namespace Modules\Api;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Modules\Api\Serializer\Adapter\AdapterInterface;
use InvalidArgumentException;
use RuntimeException;

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
     * @param  string $adapter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function addAdapter($name, $adapter)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException(sprintf('%s expects parameter 1 to be string, %s given', __METHOD__, gettype($name)));
        }

        if (is_string($adapter) and !class_exists($adapter)) {
            throw new RuntimeException(sprintf("Class '%s' not found in %s", $adapter, __FILE__));
        }

        $adapter = is_string($adapter) ? new $adapter : $adapter;

        if (!$adapter instanceof AdapterInterface) {
            throw new InvalidArgumentException(sprintf('Argument 2 passed to %s must implement %s\Serializer\Adapter\AdapterInterface, %s given', __METHOD__, __NAMESPACE__,  (is_object($adapter) ? get_class($adapter) : gettype($adapter))));
        }

        $this->adapters[$name] = $adapter;

        return $this;
    }

    /**
     * @param  string $name
     * @return Serializer\Adapter\AdapterInterface
     * @throws \RuntimeException
     */
    public function getAdapter($name)
    {
        if (!$this->hasAdapter($name)) {
            throw new RuntimeException(sprintf('Adapter "%s" not found.', $name));
        }

        $adapter = $this->adapters[$name];

        return is_object($adapter) ? $adapter : new $adapter;
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
