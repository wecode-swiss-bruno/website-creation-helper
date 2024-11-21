<?php

namespace Wecode\WebsiteCreationHelper\Exceptions;

class FrameworkNotFoundException extends \Exception
{
    public function __construct(string $framework)
    {
        parent::__construct("Framework '$framework' not found or not supported.");
    }
} 