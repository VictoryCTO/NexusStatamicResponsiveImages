<?php

namespace VictoryCTO\NexusResponsiveImages\Exceptions;

use Exception;

class AssetNotFoundException extends NexusResponsiveImagesException
{
    public static function create($assetParam)
    {
        return new self("Could not find asset {$assetParam}");
    }
}
