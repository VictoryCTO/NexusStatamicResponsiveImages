<?php

namespace VictoryCTO\NexusResponsiveImages;

use Illuminate\Support\Facades\Blade;
/*use VictoryCTO\NexusResponsiveImages\Commands\GenerateResponsiveVersionsCommand;
use VictoryCTO\NexusResponsiveImages\Commands\RegenerateResponsiveVersionsCommand;
use VictoryCTO\NexusResponsiveImages\Fieldtypes\ResponsiveFieldtype;
use VictoryCTO\NexusResponsiveImages\GraphQL\BreakpointType;
use VictoryCTO\NexusResponsiveImages\GraphQL\ResponsiveField;
use VictoryCTO\NexusResponsiveImages\GraphQL\ResponsiveFieldType as GraphQLResponsiveFieldType;
use VictoryCTO\NexusResponsiveImages\Jobs\GenerateImageJob;
use VictoryCTO\NexusResponsiveImages\Listeners\GenerateResponsiveVersions;*/
use VictoryCTO\NexusResponsiveImages\Tags\NexusResponsiveTag;
use Statamic\Events\AssetUploaded;
use Statamic\Facades\GraphQL;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        NexusResponsiveTag::class,
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
        /*AssetUploaded::class => [
            GenerateResponsiveVersions::class,
        ],*/
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
        $this->mergeConfigFrom(__DIR__.'/../config/responsive-images.php', 'statamic.nexus.responsive-images');

        $this->publishes([
            __DIR__.'/../config/responsive-images.php' => config_path('statamic/nexus/responsive-images.php'),
        ], 'responsive-images-config');

        return $this;
    }

    protected function bootDirectives(): self
    {
        Blade::directive('nexus-responsive', function ($arguments) {
            return "<?php echo NexusResponsiveTag::render({$arguments}) ?>";
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
