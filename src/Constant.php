<?php

namespace Constant;

/**
 * Class Constant
 * @package Constant
 */
final class Constant
{
    /**
     * @var Constant[]
     */
    private static $instances = [];

    /**
     * @var null|string
     */
    private $name = null;
    /**
     * @var null|mixed
     */
    private $value = null;
    /**
     * @var Scope|null
     */
    private $scope = null;

    /**
     * Constant constructor.
     *
     * @param string $name
     * @param Scope  $scope
     */
    private function __construct(string $name, Scope $scope)
    {
        $this->name  = $name;
        $this->scope = $scope;
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * @param string $name
     *
     * @return Constant
     */
    public static function Create(string $name)
    {
        $scope = new Scope($name, new Trace(['function' => 'let']));
        if (!array_key_exists($scope->getHash(), self::$instances)) {
            return new self($name, $scope);
        }

        return self::$instances[$scope->getHash()];
    }

    /**
     * @param $value
     *
     * @throws \Exception
     */
    public function be($value)
    {
        $this->ensureUniqueness();
        $this->ensureExistence();

        $this->value = $value;
    }

    /**
     * @throws \Exception
     */
    private function ensureUniqueness()
    {
        if ($this->value !== null) {
            throw new \Exception(sprintf('Konstante "%s" ist bereits belegt', $this->name));
        }
    }

    /**
     *
     */
    private function ensureExistence()
    {
        $hash = $this->scope->getHash();
        if ($this->value === null && !array_key_exists($hash, self::$instances)) {
            //            print 'ADDED ' . $this->name . '(' . $hash . ')' . PHP_EOL;
            self::$instances[$hash] = $this;
        }
    }

    /**
     * @param array ...$args
     *
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return call_user_func_array($this->value, $args);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}

/**
 * @param string $name
 *
 * @return Constant
 */
function let(string $name)
{
    return Constant::Create($name);
}