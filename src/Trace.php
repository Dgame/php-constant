<?php

namespace Dgame\Constant;

/**
 * Class Trace
 * @package Dgame\Constant
 */
final class Trace
{
    /**
     * @var BackTrace|null
     */
    private $trace = null;
    /**
     * @var null|string
     */
    private $hash = null;

    /**
     * Trace constructor.
     *
     * @param string $function
     */
    public function __construct(string $function)
    {
        $this->trace = new BackTrace($function);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHashOf(string $name) : string
    {
        if ($this->hash === null) {
            $this->generateHashOf($name);
        }

        return $this->hash;
    }

    /**
     * @param string $name
     */
    private function generateHashOf(string $name)
    {
        $hash = $name;
        foreach ($this->trace->getBackTrace() as $frame) {
            $hash .= implode($frame);
            if (array_key_exists('class', $frame)) {
                $hash .= $frame['class'];
            }
        }

        $hash .= 'global';

        $this->hash = md5($hash);
    }
}