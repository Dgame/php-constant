<?php

namespace Dgame\Constant;

/**
 * Class BackTrace
 * @package Dgame\Constant
 */
final class BackTrace
{
    const LIMIT = 20;

    /**
     * @var null|string
     */
    private $function = null;
    /**
     * @var array
     */
    private $trace = [];

    /**
     * BackTrace constructor.
     *
     * @param string $function
     */
    public function __construct(string $function)
    {
        $this->function = $function;

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, self::LIMIT);
        $trace = $this->filter($trace);

        $this->trace = $trace;
    }

    /**
     * @param array $trace
     *
     * @return array
     */
    private function filter(array $trace) : array
    {
        for ($i = 0; $i < self::LIMIT; $i++) {
            if (!array_key_exists($i, $trace)) {
                break;
            }

            if ($trace[$i]['function'] === $this->function) {
                unset($trace[$i]);
                break;
            }

            unset($trace[$i]);
        }

        return $trace;
    }

    /**
     * @return array
     */
    public function getBackTrace() : array
    {
        return $this->trace;
    }

    /**
     * @return string
     */
    public function getFunction() : string
    {
        return $this->function;
    }
}