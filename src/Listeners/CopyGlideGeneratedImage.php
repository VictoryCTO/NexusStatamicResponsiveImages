<?php

namespace VictoryCTO\NexusResponsiveImages\Listeners;

use \Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use \Statamic\Events\GlideImageGenerated;
use \Illuminate\Support\Facades\Storage;
use \Illuminate\Http\File;
use \VictoryCTO\NexusResponsiveImages\FileUtils;

class CopyGlideGeneratedImage implements ShouldQueue
{
    /**
     * Handle the events.
     *
     * @param  GlideImageGenerated  $event
     * @return void
     */
    public function handle(GlideImageGenerated $event)
    {
        $path = $event->path;



        $disk = FileUtils::ServeGeneratedImagesFromDisk();
        if(!$disk) {
            Log::debug('statamic.nexus.responsive-images.copy_generated_images_to_disk is not set...nothing to do');
            exit;
        }

        $parts = explode('/', $path);
        array_unshift($parts, 'img');
        $fileName = array_pop($parts);
        $filePath = implode('/', $parts);

        $localPath = public_path('img/') . $path;
        $remotePath = $filePath . '/' . $fileName ;

        Cache::remember('glideimage-'.md5($remotePath), 300, function() use ($disk, $remotePath, $localPath, $fileName, $filePath) {;
            Log::debug('Cache Does Not Exist');
            Log::debug('glideimage-'.md5($remotePath));
             //does the file already exist?
            if ( Storage::disk( $disk )->exists( $remotePath ) ) {
                Log::debug('File exists, nothing to do');
            } else {
                Log::debug(sprintf('Attempting to copy %s to %s as %s', $localPath, $disk, $remotePath));

                $job = Storage::disk( $disk )->putFileAs($filePath, new File($localPath), $fileName, 'public');

                if($job===false) {
                    //what should we do with a failed copying job??
                    Log::error(sprintf('Error copying %s to %s as %s', $localPath, $disk, $remotePath));
                }
            }
            return 1;
        });
    }
}
