<?php

namespace PawelMysior\Publishable\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class PublishableTest extends TestCase
{
    #[Test]
    public function posts_with_published_at_date_in_the_past_are_published()
    {
        $post = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);
        
        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isUnpublished());
    }

    #[Test]
    public function posts_with_published_at_date_set_as_now_are_published()
    {
        $post = Post::create([
            'published_at' => Carbon::now(),
        ]);

        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isUnpublished());
    }

    #[Test]
    public function posts_with_published_at_date_in_the_future_are_not_published()
    {
        $post = Post::create([
            'published_at' => Carbon::tomorrow(),
        ]);

        $this->assertFalse($post->isPublished());
        $this->assertTrue($post->isUnpublished());
    }

    #[Test]
    public function posts_with_published_at_set_as_null_are_not_published()
    {
        $post = Post::create([
            'published_at' => null,
        ]);
        
        $this->assertFalse($post->isPublished());
        $this->assertTrue($post->isUnpublished());
    }

    #[Test]
    public function publishing_post_sets_the_published_at_date_as_now()
    {
        $post = Post::create([
            'published_at' => null,
        ]);
        
        $post->publish();
        
        $this->assertEquals(Carbon::now()->timestamp, $post->published_at->timestamp);
    }

    #[Test]
    public function unpublishing_post_sets_the_published_at_date_as_null()
    {
        $post = Post::create([
            'published_at' => Carbon::now(),
        ]);
        
        $post->unpublish();
        
        $this->assertNull($post->published_at);
    }

    #[Test]
    public function test_published_scope()
    {
        $firstPublishedPost = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);
        $secondPublishedPost = Post::create([
            'published_at' => Carbon::now(),
        ]);
        
        $firstUnpublishedPost = Post::create([
            'published_at' => null,
        ]);
        $secondUnpublishedPost = Post::create([
            'published_at' => Carbon::tomorrow(),
        ]);
        
        $posts = Post::published()->get();
        
        $this->assertTrue($posts->contains($firstPublishedPost));
        $this->assertTrue($posts->contains($secondPublishedPost));
        $this->assertFalse($posts->contains($firstUnpublishedPost));
        $this->assertFalse($posts->contains($secondUnpublishedPost));
    }
    
    #[Test]
    public function test_unpublished_scope()
    {
        $firstPublishedPost = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);
        $secondPublishedPost = Post::create([
            'published_at' => Carbon::now(),
        ]);

        $firstUnpublishedPost = Post::create([
            'published_at' => null,
        ]);
        $secondUnpublishedPost = Post::create([
            'published_at' => Carbon::tomorrow(),
        ]);

        $posts = Post::unpublished()->get();

        $this->assertFalse($posts->contains($firstPublishedPost));
        $this->assertFalse($posts->contains($secondPublishedPost));
        $this->assertTrue($posts->contains($firstUnpublishedPost));
        $this->assertTrue($posts->contains($secondUnpublishedPost));
    }

    #[Test]
    public function test_published_at_field_is_not_fillable()
    {
        $post = new Post();
        $this->assertNotContains('published_at', $post->getFillable());
    }

    #[Test]
    public function test_publish_quietly_function_does_not_fire_model_events()
    {
        // Fake events so we can test on them.
        Event::fake();

        // Create a post.
        $post = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);

        // Run publish Quietly Function.
        $post->publishQuietly();

        // Assert it doesn't trigger the updated event.
        Event::assertNotDispatched('eloquent.updated: PawelMysior\Publishable\Tests\Post');
    }

    #[Test]
    public function test_unpublish_quietly_function_does_not_fire_model_events()
    {
        // Fake events so we can test on them.
        Event::fake();

        // Create a post.
        $post = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);

        // Run publish Quietly Function.
        $post->unpublishQuietly();

        // Assert it doesn't trigger the updated event.
        Event::assertNotDispatched('eloquent.updated: PawelMysior\Publishable\Tests\Post');
    }
}
