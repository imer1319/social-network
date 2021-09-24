<?php

namespace Tests\Unit\Listeners;

use App\Events\ModelLiked;
use App\Models\Status;
use App\Models\User;
use App\Notifications\NewLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class SendNewLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_sent_when_a_user_receives_a_new_like()
    {
        Notification::fake();

        $statusOwner = User::factory()->create();
        $likeSender = User::factory()->create();

        $status = Status::factory()->create(['user_id' => $statusOwner->id]);

        $status->likes()->create([
            'user_id' => $likeSender->id
        ]);

        ModelLiked::dispatch($status, $likeSender);

        Notification::assertSentTo(
            $statusOwner,
            NewLikeNotification::class,
            function ($notification, $channels) use ($likeSender, $status){
                $this->assertContains('database', $channels);
                $this->assertContains('broadcast', $channels);
                $this->assertTrue($notification->model->is($status));
                $this->assertTrue($notification->likeSender->is($likeSender));
                $this->assertInstanceOf(BroadcastMessage::class, $notification->toBroadcast($status->user));
                return true;
            }
        );
    }
}
