<?php

namespace Dgame\Constant;

use Constant\FormatException;

/**
 * Class Constant
 * @package Dgame\Constant
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
     * @var null|string
     */
    private $hash = null;
    /**
     * @var mixed
     */
    private $value = null;

    /**
     * Constant constructor.
     *
     * @param string $name
     * @param string $hash
     */
    private function __construct(string $name, string $hash)
    {
        $this->name = $name;
        $this->hash = $hash;
    }

    /**
     * @param string $name
     * @param Trace  $trace
     *
     * @return Constant
     */
    public static function Instance(string $name, Trace $trace) : Constant
    {
        $hash = $trace->getHashOf($name);
        if (self::HasInstance($hash)) {
            return self::$instances[$hash];
        }

        return new self($name, $hash);
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    private static function HasInstance(string $hash) : bool
    {
        return array_key_exists($hash, self::$instances);
    }

    /**
     * @param Constant $constant
     */
    private static function SaveInstance(Constant $constant)
    {
        $hash = $constant->getHash();
        if (!self::HasInstance($hash)) {
            self::$instances[$hash] = $constant;
        }
    }

    /**
     * @param $value
     *
     * @throws FormatException
     */
    public function be($value)
    {
        if (!empty($this->value)) {
            throw new FormatException('Value of "%s" is already set', $this->name);
        } else {
            self::SaveInstance($this);
        }

        $this->value = $value;
    }

    /**
     * @return string
     * @throws FormatException
     */
    public function __toString() : string
    {
        return (string) $this->value;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isValid() : bool
    {
        return $this->value !== null;
    }
}

/**
 * @param string $name
 *
 * @return Constant
 */
function let(string $name) : Constant
{
    return Constant::Instance($name, new Trace(__FUNCTION__));
}