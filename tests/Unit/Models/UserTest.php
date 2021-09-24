<?php

namespace Tests\Unit\Models;

use App\Models\Friendship;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function route_key_name_is_set_to_name()
    {
        $user = User::factory()->make();

        $this->assertEquals('name', $user->getRouteKeyName());
    }

    /** @test */
    public function user_has_a_link_to_their_profile()
    {
        $user = User::factory()->make();

        $this->assertEquals(route('users.show', $user), $user->link());
    }

    /** @test */
    public function user_has_an_avatar()
    {
        $user = User::factory()->make();

        $this->assertEquals(
            'https://sintesis.com.mx/wp-content/uploads/2020/06/101707535_2710625245873732_5732675811283042304_n.jpg',
            $user->avatar()
        );
        $this->assertEquals(
            'https://sintesis.com.mx/wp-content/uploads/2020/06/101707535_2710625245873732_5732675811283042304_n.jpg',
            $user->avatar
        );
    }

    /** @test */
    public function a_user_has_many_statuses()
    {
        $user = User::factory()->create();

        Status::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Status::class, $user->statuses->first());
    }

    /** @test */
    public function a_user_can_send_friend_request()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $friendship = $sender->sendFriendRequestTo($recipient);

        $this->assertTrue($friendship->sender->is($sender));
        $this->assertTrue($friendship->recipient->is($recipient));
    }

    /** @test */
    public function a_user_can_accept_friend_request()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);

        $friendship = $recipient->acceptFriendRequestFrom($sender);

        $this->assertEquals('accepted', $friendship->status);
    }

    /** @test */
    public function a_user_can_deny_friend_request()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);

        $friendship = $recipient->denyFriendRequestFrom($sender);

        $this->assertEquals('denied', $friendship->status);
    }

    /** @test */
    public function a_user_can_get_all_their_friend_request()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);

        $this->assertCount(1, $recipient->friendshipRequestReceived);
        $this->assertInstanceOf(Friendship::class, $recipient->friendshipRequestReceived->first());

        $this->assertCount(1, $recipient->friendshipRequestReceived);
        $this->assertInstanceOf(Friendship::class, $sender->friendshipRequestSent->first());
    }

    /** @test */
    public function a_user_can_get_their_friends()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);

        $this->assertCount(0, $recipient->friends());
        $this->assertCount(0, $sender->friends());

        $recipient->acceptFriendRequestFrom($sender);

        $this->assertCount(1, $recipient->friends());
        $this->assertCount(1, $sender->friends());

        $this->assertEquals($recipient->name, $sender->friends()->first()->name);
        $this->assertEquals($sender->name, $recipient->friends()->first()->name);
    }
}
