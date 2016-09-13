<?php

namespace Dgame\Constant;

defined('DEBUG_BACKTRACE_LEVEL') or define('DEBUG_BACKTRACE_LEVEL', 5);

/**
 * Class Tracer
 * @package Dgame\Constant
 */
final class Tracer
{
    /**
     * @var array
     */
    private $trace = [];

    /**
     * Tracer constructor.
     *
     * @param string $function
     * @param array  $backtrace
     */
    public function __construct(string $function, array $backtrace)
    {
        for ($i = 0, $c = count($backtrace); $i < $c; $i++) {
            if ($backtrace[$i]['function'] === $function) {
                break;
            }
        }

        $this->trace = array_slice($backtrace, $i + 1);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHash(string $name): string
    {
        foreach ($this->trace as $trace) {
            $name .= implode($trace);
        }

        return $name;
    }
}