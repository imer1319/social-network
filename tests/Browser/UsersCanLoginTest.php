<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function registered_users_can_login()
    {
        User::factory()->create(['email'=>'imer@imer.com']);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email','imer@imer.com')
                ->type('password','password')
                ->press('@login-btn')
                ->assertPathIs('/')
                ->assertAuthenticated()
            ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function user_cannot_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email','ImerMamani')
                ->press('@login-btn')
                ->assertPathIs('/login')
            ;
        });
    }
}
