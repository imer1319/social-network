<?php

namespace Tests\Browser;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;


    /**
     * @test
     * @throws \Throwable
     */
    public function guest_users_cannot_like_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use($status){
            $browser->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->assertPathIs('/login')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_like_and_unlike_statuses()
    {
        $user = User::factory()->create();

        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use($status, $user){
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count',0)
                ->press('@like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->assertSeeIn('@likes-count',1)

                ->press('@like-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA')
                ->assertSeeIn('@likes-count',0)
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_likes_and_unlikes_in_real_time()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use($status, $user, $user2){
            $browser1->loginAs($user2)->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count',0)
                ->press('@like-btn')
                ->waitForText('TE GUSTA', 10)
            ;

            $browser1->assertSeeIn('@likes_count', 1);

            $browser2
                ->press('@like-btn')
                ->waitForText('TE GUSTA');
            $browser1->assertSeeIn('@likes_count', 0);
        });
    }
}
