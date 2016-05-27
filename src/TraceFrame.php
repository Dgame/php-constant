<?php

namespace Constant;

/**
 * Class TraceFrame
 * @package Constant
 */
final class TraceFrame
{
    /**
     * @var array
     */
    private $frame = [];

    /**
     * TraceFrame constructor.
     *
     * @param array $frame
     */
    public function __construct(array $frame)
    {
        if (!array_key_exists('class', $frame)) {
            $frame['function'] = sprintf('%s\\%s', __NAMESPACE__, $frame['function']);
        }

        $this->frame = $frame;
    }

    /**
     * @param array $frame
     *
     * @return bool
     */
    public function match(array $frame)
    {
        if (array_key_exists('class', $this->frame)) {
            if (!array_key_exists('class', $frame)) {
                return false;
            }

            if ($this->frame['class'] !== $frame['class']) {
                return false;
            }
        } else if (array_key_exists('class', $frame)) {
            return false;
        }

        return $this->frame['function'] === $frame['function'];
    }
}