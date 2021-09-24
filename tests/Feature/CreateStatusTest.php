<?php

namespace Tests\Feature;

use App\Events\StatusCreated;
use App\Http\Resources\StatusResource;
use App\Models\Status;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_create_statuses()
    {
        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer estado']);

        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {
        Event::fake([StatusCreated::class]);

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer estado']);


        $response->assertJson([
            'data' => ['body' => 'Mi primer estado']
        ]);

        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'Mi primer estado'
        ]);
    }

    /** @test */
    public function a_event_is_fired_when_a_status_is_created()
    {
        Event::fake([StatusCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('statuses.store'), ['body' => 'Mi primer estado']);

        Event::assertDispatched(StatusCreated::class, function ($statusCreatedEvent) {
            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertTrue(Status::first()->is($statusCreatedEvent->status->resource));
            $this->assertEventChannelType('public', $statusCreatedEvent);
            $this->assertEventChannelName('statuses', $statusCreatedEvent);
            $this->assertDontBroadcastToCurrentUser($statusCreatedEvent);

            return true;
        });
    }

    /** @test */
    public function a_status_requires_a_body()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    /** @test */
    public function a_status_body_requires_a_minimum_length()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '1234']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}
