<?php

namespace Modules\Api;

use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application; 

class Formatter
{
    /**
     * @var array
     */
    protected $format;

    /**
     * Constructor.
     * 
     * @param  array $format
     * @return void
     */
    public function __construct($format = null)
    {
        if ($format !== null) {
            $this->setFormat($format);
        }
    }

    /**
     * @param  array $format
     * @return void
     */
    public function format($args)
    {
        $formatted = $this->getFormat();

        $callback = function (&$alias, $field, $args) { 
            if (array_key_exists($alias, $args)) {
                $alias = $args[$alias];
            }
        };

        array_walk_recursive($formatted, $callback, $args);

        return $formatted;
    }

    /**
     * @return array
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * @param  array $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
    
        return $this;
    }
}
