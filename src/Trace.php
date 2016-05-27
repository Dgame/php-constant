<?php

namespace Constant;

/**
 * Class Trace
 * @package Constant
 */
final class Trace
{
    /**
     * @var array
     */
    private static $Parts = ['file', 'class', 'function', 'line'];

    /**
     * @var array
     */
    private $trace = [];

    /**
     * Trace constructor.
     *
     * @param array $frame
     */
    public function __construct(array $frame)
    {
        $frames = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $this->filter($frames, new TraceFrame($frame));
        $this->extract($frames);
    }

    /**
     * @param array      $frames
     * @param TraceFrame $traceFrame
     */
    private function filter(array &$frames, TraceFrame $traceFrame)
    {
        foreach ($frames as $idx => $frame) {
            unset($frames[$idx]);
            if ($traceFrame->match($frame)) {
                break;
            }
        }
    }

    /**
     * @param array $frames
     */
    private function extract(array &$frames)
    {
        foreach ($frames as $frame) {
            $trace = [];
            foreach (self::$Parts as $part) {
                if (array_key_exists($part, $frame)) {
                    $trace[$part] = $frame[$part];
                }
            }
            $this->trace[] = $trace;
        }

        if (empty($this->trace)) {
            $this->trace[] = ['global'];
        }
    }

    /**
     * @return array
     */
    public function getStackTrace()
    {
        return $this->trace;
    }
}