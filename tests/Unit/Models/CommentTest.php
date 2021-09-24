<?php

namespace Tests\Unit\Models;

use App\Models\Status;
use App\Traits\HasLikes;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_comment_belongs_to_a_user()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(User::class, $comment->user);
    }

    /** @test */
    public function a_comment_belongs_to_a_status()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Status::class, $comment->status);
    }

    /** @test */
    public function a_comment_model_must_use_the_trait_has_likes()
    {
        $this->assertClassUsesTrait(HasLikes::class,Comment::class);
    }

    /** @test */
    public function a_comment_must_have_a_link()
    {
        $comment = Comment::factory()->create();

        $this->assertEquals(route('statuses.show', $comment->status_id) . "#comment-" . $comment->id, $comment->path());
    }
}
