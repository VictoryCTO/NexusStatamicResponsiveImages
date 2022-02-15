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
}
