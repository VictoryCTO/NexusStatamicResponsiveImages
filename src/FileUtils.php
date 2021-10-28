<?php

namespace VictoryCTO\NexusResponsiveImages;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Statamic\Assets\Asset;
use \Statamic\Contracts\Imaging\ImageManipulator;
use Statamic\Facades\Asset as AssetFacade;
use Statamic\Fields\Value;
use \Statamic\Imaging\GlideImageManipulator;
use Illuminate\Support\Facades\Storage;
use VictoryCTO\NexusResponsiveImages\Exceptions\DiskNotConfiguredException;
use VictoryCTO\NexusResponsiveImages\Exceptions\AssetNotFoundException;

class FileUtils {


    public static function retrieveAsset($assetParam): Asset
    {
        if ($assetParam instanceof Asset) {
            return $assetParam;
        }

        if (is_string($assetParam)) {
            $asset = AssetFacade::findByUrl($assetParam);

            if (! $asset) {
                $asset = AssetFacade::findByPath($assetParam);
            }
        }

        if ($assetParam instanceof Value) {
            $asset = $assetParam->value();

            if ($asset instanceof Collection) {
                $asset = $asset->first();
            }
        }

        if (is_array($assetParam) && isset($assetParam['url'])) {
            $asset = AssetFacade::findByUrl($assetParam['url']);
        }

        if (! isset($asset)) {
            throw AssetNotFoundException::create($assetParam);
        }

        return $asset;
    }

    public static function imageUrl( $image, $params ): String
    {
        $image = static::retrieveAsset( $image );

        $path = app( ImageManipulator::class )->item( $image )->params( $params )->build();

        //are we serving this from a remote disk or local?
        $disk = static::ServeGeneratedImagesFromDisk();
        $url = ($disk && Storage::disk( $disk )->exists( $path ) ) ? rtrim( config( 'filesystems.disks.'.$disk.'.url' ), "/" ) : '' ;

        return $url . $path;

        //$url = rtrim( config('filesystems.disks.'.$disk.'.url'), "/" );
        //return $url . app(ImageManipulator::class)->item($image)->params($params)->build();
        //return app(GlideImageManipulator::class)->item($image)->params($params)->build();
    }

    public static function ServeGeneratedImagesFromDisk(): String
    {
        $disk = config('statamic.nexus.responsive-images.copy_generated_images_to_disk');

        //if there is no disk configured
        if(!$disk) {
            Log::debug('statamic.nexus.responsive-images.copy_generated_images_to_disk is not set');
            return false;

        //is the disk viable
        } elseif(!config('filesystems.disks.'.$disk)) {
            throw DiskNotConfiguredException::create($disk);
        }

        return $disk;
    }
}