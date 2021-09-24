<?php

namespace Tests\Browser;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanSeeProfilesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_profiles()
    {
        $user = User::factory()->create();
        $statuses = Status::factory(2)->create(['user_id' => $user->id]);
        $otherStatus = Status::factory()->create();
        $this->browse(function (Browser $browser) use($user, $statuses, $otherStatus) {
            $browser->visit("/@{$user->name}")
                ->assertSee($user->name)
                ->waitForText($statuses->first()->body)
                ->assertSee($statuses->first()->body)
                ->assertSee($statuses->last()->body)
                ->assertDontSee($otherStatus->body)
            ;
        });
    }
}
