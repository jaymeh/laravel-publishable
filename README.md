# Publishable

Toggle the published state of your Eloquent models easily.

## Installation

You can install the package via composer:

```bash
composer require pawelmysior/laravel-publishable
```

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
```

A post is considered published when the `published_at` is not null and in the past.

A post is considered unpublished when the `published_at` is null or in the future.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
