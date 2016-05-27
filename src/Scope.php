<?php

namespace Constant;

/**
 * Class Scope
 * @package Constant
 */
final class Scope
{
    /**
     * @var null|string
     */
    private $hash = null;

    /**
     * Scope constructor.
     *
     * @param string $name
     * @param Trace  $trace
     */
    public function __construct(string $name, Trace $trace)
    {
        $identifier = $name;
        foreach ($trace->getStackTrace() as $frame) {
            $identifier .= implode($frame);
        }

        $this->hash = md5($identifier);
    }

    /**
     * @param Scope $scope
     *
     * @return bool
     */
    public function equals(Scope $scope)
    {
        return $this->getHash() === $scope->getHash();
    }

    /**
     * @return null|string
     */
    public function getHash()
    {
        return $this->hash;
    }
}