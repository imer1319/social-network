<?php

namespace Tests\Feature;

use App\Events\CommentCreated;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_create_comments()
    {
        $status = Status::factory()->create();
        $comment = ['body' => "Mi primer comentario"];

        $response = $this->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_comment_statuses()
    {
        $this->withoutExceptionHandling();
        $status = Status::factory()->create();
        $user = User::factory()->create();
        $comment = ['body' => "Mi primer comentario"];

        $response = $this->actingAs($user)->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertJson([
           'data' => ['body' => $comment['body']]
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'status_id' => $status->id,
            'body' => $comment['body']
        ]);
    }

    /** @test*/
    public function a_comment_requires_a_body()
    {
        $status = Status::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.comments.store',$status),['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message','errors' => ['body']
        ]);
    }

    /** @test */
    public function a_event_is_fired_when_a_comment_is_created()
    {
        Event::fake([CommentCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $status = Status::factory()->create();
        $user = User::factory()->create();
        $comment = ['body' => "Mi primer comentario"];

        $this->actingAs($user)
            ->postJson(route('statuses.comments.store',$status),$comment);

        Event::assertDispatched(CommentCreated::class, function ($commentCreatedEvent) {
            $this->assertInstanceOf(CommentResource::class, $commentCreatedEvent->comment);
            $this->assertTrue(Comment::first()->is($commentCreatedEvent->comment->resource));
            $this->assertEventChannelType('public', $commentCreatedEvent);
            $this->assertEventChannelName("statuses.{$commentCreatedEvent->comment->status_id}.comments", $commentCreatedEvent);
            $this->assertDontBroadcastToCurrentUser($commentCreatedEvent);

            return true;
        });
    }
}
