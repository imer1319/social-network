<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function users_can_see_statuses_in_real_time()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use($user1, $user2) {
            $browser1->loginAs($user1)
                ->visit('/');

            $browser2->loginAs($user2)
                ->visit('/')
                ->type('body','Mi primer status')
                ->press('#create-status')
                ->waitForText('Mi primer status',8)
                ->assertSee('Mi primer status')
                ->assertSee($user2->name);

            $browser1->waitForText('Mi primer status')
                ->assertSee('Mi primer status')
                ->assertSee($user2->name);
        });
    }

    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function users_can_create_statuses()
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/')
                    ->type('body','Mi primer status')
                    ->press('#create-status')
                    ->waitForText('Mi primer status')
                    ->assertSee('Mi primer status')
                ->assertSee($user->name);
        });
    }
}
