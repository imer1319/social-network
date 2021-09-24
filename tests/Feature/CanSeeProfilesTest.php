<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_profiles_test()
    {
        $this->withoutExceptionHandling();

        User::factory()->create(['name' => 'Imer']);
        $this->get('@Imer')->assertSee('Imer');
    }
}
