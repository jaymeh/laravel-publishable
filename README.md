# Publishable

Toggle the published state of your Eloquent models easily.

## Installation

You can install the package via composer:

```bash
composer require pawelmysior/laravel-publishable
```

## Versions

For details about which version of this package to use with your Laravel version please see the table below:

| Laravel Version | Package Version |
| --------------- | --------------- |
| <9.x            | 1.x             |
| 9.x             | 2.x             |

## Preparation

To start you need to add a `published_at` nullable timestamp column to your table.

Put this in your table migration:

```php
$table->timestamp('published_at')->nullable();
```

Now use the trait on the model

```php
<?php
 
namespace App;
  
use Illuminate\Database\Eloquent\Model;
use PawelMysior\Publishable\Publishable;
 
class Post extends Model
{
    use Publishable;
}
```

## Add Fillable Attribute

In Version 1.x, the `published()` function handles the update in a way where Laravel expects that the `published_at` field is fillable. As of Version 2.x, we not use fill functionality so the fillable attribute is no longer required.

## Usage

You can now use those features:

```php
// Get only published posts
Post::published()->get();
 
// Get only unpublished posts
Post::unpublished()->get();
 
// Check if the post is published
$post->isPublished();
 
// Check if the post is unpublished
$post->isUnpublished();
 
// Publish the post
$post->publish();
 
// Unpublish the post
$post->unpublish();

// Publish the post without firing model events
$post->publishQuietly();
 
// Unpublish the post without firing model events
$post->unpublishQuietly();
```

A post is considered published when the `published_at` is not null and in the past.

A post is considered unpublished when the `published_at` is null or in the future.

## Security

If you discover any security-related issues, please email security@jaymeh.co.uk instead of using the issue tracker.

## Contributing

Any contributions to this repository are welcomed. Please be aware that we are using [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/#summary) to assist in self documentation and reduce manual work involved with releases.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
