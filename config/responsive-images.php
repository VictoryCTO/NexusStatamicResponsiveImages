<?php

return [

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
    | These should mirror the settings in resources/assets/scss/media-queries.scss which override the defaults in node_modules/bootstrap/scss/_variables.scss
    |
    */

    'breakpoints' => [
        'xs' => 576,        // from resources/assets/scss/media-queries.scss
        'sm' => 768,        // from resources/assets/scss/media-queries.scss
        'md' => 992,        // from resources/assets/scss/media-queries.scss
        'lg' => 1200,       // from resources/assets/scss/media-queries.scss
        'xl' => 1200,       // from node_modules/bootstrap/scss/_variables.scss
        'xxl' => 1400       // from node_modules/bootstrap/scss/_variables.scss
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
    | These should mirror the settings in node_modules/bootstrap/scss/_variables.scss. Missing indices are
    |
    */

    'container_max_widths' => [
        'sm' => 540,
        'md' => 720,
        'lg' => 960,
        'xl' => 1140,
        'xxl' => 1320,
    ],

];
