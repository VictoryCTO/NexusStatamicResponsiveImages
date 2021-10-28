<?php

namespace VictoryCTO\NexusResponsiveImages\Exceptions;

use Exception;
use Statamic\Assets\Asset;

class InvalidAssetException extends NexusResponsiveImagesException
{
    public static function zeroWidthOrHeight(Asset $asset)
    {
        return new self("Asset {$asset->id()} has 0 width or height. Cannot create responsive variants.");
    }
}
