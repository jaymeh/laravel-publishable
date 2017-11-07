<?php

namespace PawelMysior\Publishable\Tests;

use Carbon\Carbon;

class PublishableTest extends TestCase
{
    /** @test */
    public function posts_with_published_at_date_in_the_past_are_published()
    {
        $post = Post::create([
            'published_at' => Carbon::yesterday(),
        ]);
        
        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isUnpublished());
    }

    /** @test */
    public function posts_with_published_at_date_set_as_now_are_published()
    {
        $post = Post::create([
            'published_at' => Carbon::now(),
        ]);

        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isUnpublished());
    }

    /** @test */
    public function posts_with_published_at_date_in_the_future_are_not_published()
    {
        $post = Post::create([
            'published_at' => Carbon::tomorrow(),
        ]);

        $this->assertFalse($post->isPublished());
        $this->assertTrue($post->isUnpublished());
    }

    /** @test */
    public function posts_with_published_at_set_as_null_are_not_published()
    {
        $post = Post::create([
            'published_at' => null,
        ]);
        
        $this->assertFalse($post->isPublished());
        $this->assertTrue($post->isUnpublished());
    }

    /** @test */
    public function publishing_post_sets_the_published_at_date_as_now()
    {
        $post = Post::create([
            'published_at' => null,
        ]);
        
        $post->publish();
        
        $this->assertEquals(Carbon::now()->timestamp, $post->published_at->timestamp);
    }

    /** @test */
    public function unpublishing_post_sets_the_published_at_date_as_null()
    {
        $post = Post::create([
            'published_at' => Carbon::now(),
        ]);
        
        $post->unpublish();
        
        $this->assertNull($post->published_at);
    }

    /** @test */
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
    
    /** @test */
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
}
