<?php

namespace VictoryCTO\NexusResponsiveImages\Tags;

use Spatie\ResponsiveImages\Tags\ResponsiveTag;
use VictoryCTO\NexusResponsiveImages\Exceptions\AssetNotFoundException;
use VictoryCTO\NexusResponsiveImages\Breakpoint;
use Spatie\ResponsiveImages\Breakpoint as SpatieBreakpoint;
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
        array_walk($elements, function(&$value, $elements) use ($breakpoints, $breakpoint_max_widths) {

            //enforce required elements
            //foreach(['element','image','width','height'] as $key) {
            foreach(['element','image'] as $key) {
                //ensure proper array config
                if(!is_array($value) || !array_key_exists($key, $value) || empty($value[$key])) throw new NexusResponsiveImagesException('You have a malformed responsive images array', $value, $elements);
            }

            //retrieve the asset / ensure image exists
            $asset = FileUtils::retrieveAsset($value['image']);

            //set up the Responsive object
            $resp = app(NexusResponsiveBgTag::class);
            $resp->setContext(['url' => $value['image']]);
            $resp->setParameters([]);
            $responsive = new Responsive($value['image'], $resp->params);

            //filter the parameter keys to breakpoint sizes (ie 'sm:src' to 'sm')
            $params = [];
            foreach($responsive->parameters as $key=>$item) {
                $bp = str_replace(':src', '', $key);
                if(array_key_exists($bp, $breakpoints)) $params[$bp] = $item;
            }

            //build the sources array with breakpoint sizes for array keys
            $value['sources'] = [];
            $asset = $responsive->asset;
            foreach(array_keys($breakpoints) as $bp) {
                if(array_key_exists($bp,$params)) $asset = $params[$bp];

                $assetWidth  = $asset->meta('width');
                $assetHeight = $asset->meta('height');
                $maxW        = $breakpoint_max_widths[$bp];
                $maxH        = $assetHeight / $assetWidth * $maxW;

                $value['sources'][$bp] = \VictoryCTO\NexusResponsiveImages\FileUtils::imageUrl( $asset, ['w'=>min( $assetWidth, $maxW ), 'h'=>min( $assetHeight, $maxH )] );
            }
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
