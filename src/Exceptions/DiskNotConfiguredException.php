<?php

namespace VictoryCTO\NexusResponsiveImages\Exceptions;

use Exception;

class DiskNotConfiguredException extends NexusResponsiveImagesException
{
    public static function create($disk)
    {
        return new self("Disk {$disk} is not configured");
    }
}
