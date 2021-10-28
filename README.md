<!-- statamic:hide -->
[![Latest Version](https://img.shields.io/github/release/victorycto/nexus-statamic-responsive-images.svg?style=flat-square)](https://github.com/victorycto/nexus-statamic-responsive-images/releases)
![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0+-FF269E?style=flat-square&link=https://statamic.com)

# Responsive Images

> Responsive Images for Statamic 3.

<!-- /statamic:hide -->

This Addon provides responsive images inspired by the [Spatie Medialibrary Package](https://github.com/spatie/laravel-medialibrary).

## Installation

Require it using Composer.

```
composer require victorycto/nexus-statamic-responsive-images
```

## Using Responsive Images

Responsive Images will generate responsive versions of the images whenever a new asset is uploaded. These presets are determined by this package and not by your own Glide presets.

We generally recommend setting `statamic.assets.image_manipulation.cache` to `false` so only images actually requested by a browser are generated. The first time the conversion is loaded will be slow, but Glide still has an internal cache that it will serve from the next time. This saves a lot on server resources and storage requirements.

## Templating

Pass an image to the `nexus-responsive` tag.

```twig
{{ nexus-responsive:image_field }}
```
```blade
@nexus-responsive( {{ image_field }})
```


This will render an image tag with the default srcsets. The tag uses JS to define the value of the sizes attribute. This way the browser will always download the correct image.

## Image ratio

You can make sure images are a certain ratio by passing a `ratio` parameter, either as a string `16/10` or as a float `1.6`.

```twig
{{ nexus-responsive:image_field ratio="16/9" }}
```
```blade
@nexus-responsive( {{ image_field }}, ['ratio'=>"16/9"] )
```

## Responsive placeholder

By default, responsive images generates a small base64 encoded placeholder to show while your image loads. If you want to disable this you can pass `placeholder="false"` to the tag.

```twig
{{ nexus-responsive:image_field placeholder="false" }}
```
```blade
@nexus-responsive( {{ image_field }}, ['placeholder'=>false] )
```
## Webp image generation

By default, responsive images generates webp variants in addition to jpg or png versions of your image, these are usually smaller. If you want to disable this functionality you can pass `webp="false"` to your tag.

```twig
{{ nexus-responsive:image_field webp="false" }}
```
```blade
@nexus-responsive( {{ image_field }}, ['webp'=>false] )
```

## Glide parameters

You can still pass any parameters from the Glide tag that you would want to, just make sure to prefix them with `glide:`.
Passing `glide:width` will consider the width as a max width, which can prevent unnecessary large images from being generated.

```twig
{{ nexus-responsive:image_field glide:blur="20" glide:width="1600" }}
```
```blade
@nexus-responsive( {{ image_field }}, ['glide'=>['blur'=>"20", 'width'=>"1600"]] )
```

## HTML Attributes

If you want to add additional attributes (for example a title attribute) to your image, you can add them as parameters to the tag, any attributes will be added to the image.

```twig
{{ nexus-responsive:image_field alt="{title}" class="my-class" }}
```
```blade
@nexus-responsive( {{ image_field }}, ['alt'=>"{title}", 'class'=>"my-class"] )
```
## Customizing the generated html

If you want to customize the generated html, you can publish the views using

```bash
php artisan vendor:publish
```

and choosing `VictoryCTO\NexusResponsiveImages\ServiceProvider`


