<?php

namespace VictoryCTO\NexusResponsiveImages\Tags;

use VictoryCTO\NexusResponsiveImages\Exceptions\AssetNotFoundException;
use VictoryCTO\NexusResponsiveImages\Breakpoint;
use VictoryCTO\NexusResponsiveImages\Exceptions\NexusResponsiveImagesException;
use VictoryCTO\NexusResponsiveImages\FileUtils;
use VictoryCTO\NexusResponsiveImages\Responsive;
use Statamic\Support\Str;
use Statamic\Tags\Tags;

class NexusResponsiveBgTag extends Tags
{
    protected static $handle = 'nexusresponsivebg';

    public static function render($elements): string
    {
        //load config breakpoints and max widths
        $breakpoints = config('statamic.nexus.responsive-images.breakpoints');
        $breakpoint_unit = config('statamic.nexus.responsive-images.breakpoint_unit');
        $container_max_widths = config('statamic.nexus.responsive-images.container_max_widths');


        //parse the data
        arsort($container_max_widths, SORT_NUMERIC); //sort this array in reverse order maintaining the indexes
        $breakpoint_max_widths = [];
        foreach($breakpoints as $bp=>$minW) {
            //handle min-width 0
            if($minW<1) $breakpoint_max_widths[$bp] = min($container_max_widths);
            //otherwise get the largest max container width possible for this breakpoint
            else {
                foreach($container_max_widths as $maxW) {
                    if($maxW<=$minW) {
                        $breakpoint_max_widths[$bp] = $maxW;
                        break;
                    }
                }
            }
        }

        //sanitize and parse passed element values (aka make sure the passed values will not mess up calculations below)
        array_walk($elements, function(&$value, $elements) {
            //enforce required elements
            foreach(['element','image','width','height'] as $key) {
                //ensure proper array config
                if(!is_array($value) || !array_key_exists($key, $value) || empty($value[$key])) throw new NexusResponsiveImagesException('You have a malformed responsive images array', $value, $elements);
            }

            //ensure image exists
            try {
                $asset = FileUtils::retrieveAsset($value['element']);
            } catch(AssetNotFoundException $ex) {
                throw new NexusResponsiveImagesException('You are missing a required asset', $value);
            }

            //enforce numeric dimensions
            $value['width'] = str_replace('px', '', $value['width'] );
            $value['height'] = str_replace('px', '', $value['height'] );
            if(!is_numeric($value['width']) || !is_numeric($value['height'])) throw new NexusResponsiveImagesException('Height and width must be numeric',$value);

            //calculate ratio
            $value['ratio'] = $value['height'] / $value['width'] ;
        });


        return view('nexus-responsive-images::responsiveBgImage',
            [
                'breakpoint_max_widths' => $breakpoint_max_widths,
                'breakpoints'           => $breakpoints,
                'breakpoint_unit'       => $breakpoint_unit,
                'elements'              => $elements,
            ])->render();
    }
}
