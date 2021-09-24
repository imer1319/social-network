<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListStatusesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_all_statuses()
    {
        $this->withoutExceptionHandling();

        $status1 = Status::factory()->create(['created_at' => now()->subDays(4)]);
        $status2 = Status::factory()->create(['created_at' => now()->subDays(3)]);
        $status3 = Status::factory()->create(['created_at' => now()->subDays(2)]);
        $status4 = Status::factory()->create(['created_at' => now()->subDays(1)]);

        $response = $this->getJson(route('statuses.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 4]
        ]);

        $response->assertJsonStructure([
            'data', 'links' =>['prev','next']
        ]);
        $this->assertEquals(
            $status4->body,
            $response->json('data.0.body')
        );
    }

    /** @test */
    public function can_get_status_for_a_specific_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $status1 = Status::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDay()]);
        $status2 = Status::factory()->create(['user_id' => $user->id,]);

        $otherStatuses = Status::factory(3)->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.statuses.index', $user));

        $response->assertJson([
            'meta' => ['total' => 2]
        ]);

        $response->assertJsonStructure([
           'data', 'links' => ['prev','next']
        ]);

        $this->assertEquals(
            $status2->body,
            $response->json('data.0.body')
        );
    }

    /** @test */
    public function can_see_individual_statuses()
    {
        $this->withoutExceptionHandling();
        $status = Status::factory()->create();

        $this->get($status->path())->assertSee($status->body);
    }
}
