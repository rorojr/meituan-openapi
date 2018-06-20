<?php

namespace MeituanOpenApi\Exception;

use LogicException;

class ServiceException extends LogicException
{
    public function __construct($message)
    {
        if (is_null($message)) {
            return;
        }

        $this->message = $message;
    }
}