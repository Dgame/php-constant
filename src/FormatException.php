<?php

namespace Constant;

/**
 * Class FormatException
 * @package Constant
 */
class FormatException extends \Exception
{
    /**
     * FormatException constructor.
     *
     * @param string $message
     * @param array  ...$args
     */
    public function __construct(string $message, ...$args)
    {
        parent::__construct(vsprintf($message, $args));
    }
}