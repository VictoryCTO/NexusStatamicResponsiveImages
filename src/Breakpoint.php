<?php

namespace VictoryCTO\NexusResponsiveImages;

use Spatie\ResponsiveImages\Breakpoint as SpatieBreakpoint;

class Breakpoint extends SpatieBreakpoint
{

    public function __construct(Asset $asset, string $label, int $value, array $parameters)
    {
        parent::__construct($asset, $label, $value, $parameters);

        $this->unit = config('statamic.nexus.responsive-images.breakpoint_unit', 'px');
    }

    /*public function buildImageJob(int $width, ?string $format = null, ?float $ratio = null): String
    {
        $params = $this->getParams($format);

        $params['width'] = $width;

        if (! is_null($ratio)) {
            $params['height'] = $width / $ratio;
        }

        return FileUtils::imageUrl($this->asset, $params);
    }*/
}
