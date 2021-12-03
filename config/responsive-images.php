<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Copy Glide generated images to another disk
    |--------------------------------------------------------------------------
    |
    | Define another disk to which to copy glide generated images
    |
    */

    'copy_generated_images_to_disk' => 's3-assets',

    /*
    |--------------------------------------------------------------------------
    | Max Width
    |--------------------------------------------------------------------------
    |
    | Define a global max-width for generated images.
    | You can override this on the tag.
    |
    */

    'max_width' => null,

    /*
    |--------------------------------------------------------------------------
    | Placeholder
    |--------------------------------------------------------------------------
    |
    | Define if you want to generate low-quality placeholders of your images.
    | You can override this on the tag.
    |
    */

    'placeholder' => true,

    /*
    |--------------------------------------------------------------------------
    | WebP
    |--------------------------------------------------------------------------
    |
    | Define if you want to generate WebP versions of your images.
    | You can override this on the tag.
    |
    */

    'webp' => false,

    /*
    |--------------------------------------------------------------------------
    | Breakpoints
    |--------------------------------------------------------------------------
    |
    | These should mirror the settings in one of these files:
    |   - resources/assets/scss/_variables.scss
    |   - resources/assets/scss/_media-queries.scss
    |   - node_modules/bootstrap/scss/_variables.scss (bootstrap defaults)
    |
    */

    'breakpoints' => [
        'xs' => 0,
        'sm' => 576,
        'md' => 768,
        'lg' => 996,
        'xl' => 1200,
        'xxl' => 1488
    ],

    /*
    |--------------------------------------------------------------------------
    | Breakpoint Unit
    |--------------------------------------------------------------------------
    |
    | The unit that will be used for the breakpoint media queries
    |
    */

    'breakpoint_unit' => 'px',

    /*
    |--------------------------------------------------------------------------
    | Container Max Widths
    |--------------------------------------------------------------------------
    |
    | These should mirror the settings in one of these files:
    |   - resources/assets/scss/_variables.scss
    |   - resources/assets/scss/_media-queries.scss
    |   - node_modules/bootstrap/scss/_variables.scss (bootstrap defaults)
    |
    */


    'container_max_widths' => [
        'sm' => 540,
        'md' => 720,
        'lg' => 960,
        'xl' => 1140,
        'xxl' => 1440,
    ],

];
