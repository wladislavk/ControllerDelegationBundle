<?php
namespace VKR\ControllerDelegationBundle\Exception;

class UndefinedRouteException extends \RuntimeException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
