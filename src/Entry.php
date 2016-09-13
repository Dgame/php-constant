<?php

namespace Dgame\Constant;

use Exception;
use function Dgame\Ensurance\ensure;

/**
 * Class Entry
 * @package Dgame\Constant
 * @property string $name
 * @property string $hash
 * @property mixed  $value
 */
final class Entry
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $hash;
    /**
     * @var mixed
     */
    private $value;

    /**
     * Entry constructor.
     *
     * @param string $name
     * @param Tracer $tracer
     */
    public function __construct(string $name, Tracer $tracer)
    {
        $this->name = $name;
        $this->hash = $tracer->getHash($name);
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param $value
     */
    public function be($value)
    {
        ensure($this->value)->isNull()->orThrow('Constant "%s" is already assigned', $this->name);

        $this->value = $value;

        Registry::Instance()->register($this);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        throw new Exception('No such property: ' . $name);
    }

    /**
     * @param string $name
     * @param        $value
     *
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        throw new Exception('Cannot set property: ' . $name);
    }
}