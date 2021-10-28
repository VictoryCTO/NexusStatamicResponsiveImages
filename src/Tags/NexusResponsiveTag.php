<?php

namespace VictoryCTO\NexusResponsiveImages\Tags;

use VictoryCTO\NexusResponsiveImages\AssetNotFoundException;
use VictoryCTO\NexusResponsiveImages\Breakpoint;
use VictoryCTO\NexusResponsiveImages\Responsive;
use Statamic\Support\Str;
use Statamic\Tags\Tags;

class NexusResponsiveTag extends Tags
{
    protected static $handle = 'nexusresponsive';

    public static function render(...$arguments): string
    {
        $asset = $arguments[0];
        $parameters = $arguments[1] ?? [];

        /** @var \VictoryCTO\NexusResponsiveImages\Tags\NexusResponsiveTag $responsive */
        $responsive = app(NexusResponsiveTag::class);
        $responsive->setContext(['url' => $asset]);
        $responsive->setParameters($parameters);

        return $responsive->wildcard('url');
    }

    public function wildcard($tag)
    {
        $this->params->put('src', $this->context->get($tag));

        return $this->index();
    }

    public function index()
    {
        try {
            $responsive = new Responsive($this->params->get('src'), $this->params);
        } catch (AssetNotFoundException $e) {
            return '';
        }

        if (in_array($responsive->asset->extension(), ['svg', 'gif'])) {
            return view('nexus-responsive-images::responsiveImage', [
                'attributeString' => $this->getAttributeString(),
                'src' => $responsive->asset->url(),
                'width' => $responsive->asset->width(),
                'height' => $responsive->assetHeight(),
                'asset' => $responsive->asset->toAugmentedArray(),
            ])->render();
        }

        $includePlaceholder = $this->includePlaceholder();

        $sources = $responsive->breakPoints()
            ->map(function (Breakpoint $breakpoint) use ($includePlaceholder) {
                return [
                    'media' => $breakpoint->getMediaString(),
                    'srcSet' => $breakpoint->getSrcSet($includePlaceholder),
                    'srcSetWebp' => $this->includeWebp()
                        ? $breakpoint->getSrcSet($includePlaceholder, 'webp')
                        : null,
                    'placeholder' => $breakpoint->placeholder(),
                ];
            });

        return view('nexus-responsive-images::responsiveImage', [
            'attributeString' => $this->getAttributeString(),
            'includePlaceholder' => $includePlaceholder,
            'placeholder' => $sources->last()['placeholder'],
            'src' => $responsive->asset->url(),
            'sources' => $sources,
            'width' => $responsive->asset->width(),
            'height' => $responsive->assetHeight(),
            'asset' => $responsive->asset->toAugmentedArray(),
        ])->render();
    }

    private function getAttributeString(): string
    {
        $breakpointPrefixes = collect(array_keys(config('statamic.nexus.responsive-images.breakpoints')))
            ->map(function ($breakpoint) {
                return "{$breakpoint}:";
            })->toArray();

        return collect($this->params)
            ->reject(function ($value, $name) use ($breakpointPrefixes) {
                if (Str::contains($name, array_merge(['src', 'placeholder', 'webp', 'ratio', 'glide:', 'default:'], $breakpointPrefixes))) {
                    return true;
                }

                return false;
            })
            ->map(function ($value, $name) {
                return $name . '="' . $value . '"';
            })->implode(' ');
    }

    private function includePlaceholder(): bool
    {
        return $this->params->has('placeholder')
            ? $this->params->get('placeholder')
            : config('statamic.nexus.responsive-images.placeholder', true);
    }

    private function includeWebp(): bool
    {
        return $this->params->has('webp')
            ? $this->params->get('webp')
            : config('statamic.nexus.responsive-images.webp', true);
    }
}
