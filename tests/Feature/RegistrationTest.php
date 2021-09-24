<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_register()
    {
        $response = $this->post(route('register'), $this->userValidData());

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users',[
            'name' => 'ImerMamani2',
            'first_name' => 'Imer',
            'last_name' => 'Mamani',
            'email' => 'imer@imer.com',
        ]);

        $this->assertTrue(
            Hash::check('password', User::first()->password),
            'The password needs to be hashed'
        );
    }

    /** @test */
    public function the_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => null])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => 1234])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => Str::random(61)])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_not_be_unique()
    {
        User::factory()->create(['name' => 'ImerMamani']);
        $this->post(
            route('register'),
            $this->userValidData(['name' => 'ImerMamani'])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_only_contain_letters_and_numbers()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => 'Imer Mamani2'])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => Str::random(2)])
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => null])
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 1234])
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => Str::random(61)])
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => Str::random(2)])
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 'Imer Mamani2'])
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_last_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => null])
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 1234])
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => Str::random(61)])
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => Str::random(2)])
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 'Imer Mamani2'])
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_email_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['email' => null])
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_a_valid_email_address()
    {
        $this->post(
            route('register'),
            $this->userValidData(['email' => 'invalid'])
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_may_not_be_greater_than_100_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['email' => Str::random(101)])
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_may_not_be_unique()
    {
        User::factory()->create(['email' => 'user@user.com']);
        $this->post(
            route('register'),
            $this->userValidData(['email' => 'user@user.com'])
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => null])
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => 1234])
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_at_least_8_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => Str::random(7)])
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_confirmed()
    {
        $this->post(
            route('register'),
            $this->userValidData([
                'password' => 'password',
                'password_confirmation' => ''
            ])
        )->assertSessionHasErrors('password');
    }

    /**
     * @param array $overrides
     * @return string[]
     */
    public function userValidData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'ImerMamani2',
            'first_name' => 'Imer',
            'last_name' => 'Mamani',
            'email' => 'imer@imer.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }
}
