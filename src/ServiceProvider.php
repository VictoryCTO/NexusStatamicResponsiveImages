<?php

namespace VictoryCTO\NexusResponsiveImages;

use Spatie\ResponsiveImages\ServiceProvider as SpatieServiceProvider;

use Illuminate\Support\Facades\Blade;

use JackSleight\StatamicBardMutator\Facades\Mutator;
use Statamic\Events\GlideImageGenerated;
use VictoryCTO\NexusResponsiveImages\Tags\NexusResponsiveTag;
use VictoryCTO\NexusResponsiveImages\Tags\NexusResponsiveBgTag;
use VictoryCTO\NexusResponsiveImages\Listeners\CopyGlideGeneratedImage;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends SpatieServiceProvider
{
    protected $tags = [
        NexusResponsiveTag::class,
        NexusResponsiveBgTag::class,
    ];

    protected $fieldtypes = [
    ];

    protected $stylesheets = [
        //__DIR__.'/../dist/css/responsive.css',
    ];

    protected $scripts = [
        //__DIR__.'/../dist/js/responsive.js',
    ];

    protected $listen = [
        GlideImageGenerated::class => [
            CopyGlideGeneratedImage::class,
        ],
    ];

    protected $commands = [
        //GenerateResponsiveVersionsCommand::class,
        //RegenerateResponsiveVersionsCommand::class,
    ];

    public function boot()
    {
        parent::boot();

        $this
            ->bootCommands()
            ->bootAddonViews()
            ->bootAddonConfig()
            ->bootDirectives()
            ->bindImageJob()
            ->bootGraphQL();


        Mutator::tag('image', function ($tag) {
            //get the asset by the url
            $asset = FileUtils::retrieveAsset($tag[0]['attrs']['src']);

            //start by closing the hanging opened tag.
            $tag = '!-- -->';

            //render the responsive tag
            $tag .= NexusResponsiveTag::render($asset);

            //now handle the hanging closing tag
            $tag .= '<!-- --';

            return $tag;
        });
    }

    protected function bootAddonViews(): self
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nexus-responsive-images');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/nexus-responsive-images'),
        ], 'nexus-responsive-images-views');

        return $this;
    }

    protected function bootAddonConfig(): self
    {
        $this->mergeConfigFrom($config = __DIR__.'/../config/responsive-images.php', 'statamic.nexus.responsive-images');

        $this->publishes([
            $config => config_path('statamic/nexus/responsive-images.php'),
        ], 'responsive-images-config');

        return $this;
    }

    protected function bootDirectives(): self
    {
        Blade::directive('nexusresponsive', function ($arguments) {
            return "<?php echo \Spatie\ResponsiveImages\Tags\ResponsiveTag::render({$arguments}) ?>";
        });

        Blade::directive('nexusresponsivebg', function ($arguments) {
            return "<?php echo \VictoryCTO\NexusResponsiveImages\Tags\NexusResponsiveBgTag::render({$arguments}) ?>";
        });

        return $this;
    }

    private function bindImageJob(): self
    {
        //$this->app->bind(GenerateImageJob::class, config('statamic.nexus.responsive-images.image_job'));

        return $this;
    }

    private function bootGraphQL(): self
    {
        /*GraphQL::addType(BreakpointType::class);
        GraphQL::addType(GraphQLResponsiveFieldType::class);

        GraphQL::addField('AssetInterface', 'responsive', function () {
            return (new ResponsiveField())->toArray();
        });*/

        return $this;
    }
}
