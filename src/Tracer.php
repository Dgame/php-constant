<?php

namespace Dgame\Constant;

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
     * @return string
     */
    public function getHash(): string
    {
        $hash = '';
        foreach ($this->trace as $trace) {
            $hash .= implode($trace);
        }

        return md5($hash);
    }
}