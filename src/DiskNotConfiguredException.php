<?php

namespace VictoryCTO\NexusResponsiveImages;

use Exception;

class DiskNotConfiguredException extends Exception
{
    public static function create($disk)
    {
        return new self("Disk {$disk} is not configured");
    }
}
