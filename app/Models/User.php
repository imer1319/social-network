<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use function Symfony\Component\Translation\t;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar'];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function link()
    {
        return route('users.show', $this);
    }

    public function avatar()
    {
        return 'https://sintesis.com.mx/wp-content/uploads/2020/06/101707535_2710625245873732_5732675811283042304_n.jpg';
    }

    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function friendshipRequestReceived()
    {
        return $this->hasMany(Friendship::class, 'recipient_id');
    }

    public function friendshipRequestSent()
    {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    public function sendFriendRequestTo($recipient)
    {
        return $this->friendshipRequestSent()
            ->firstOrcreate(['recipient_id' => $recipient->id]);
    }

    public function acceptFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestReceived()
            ->where(['sender_id' => $sender->id])->first();

        $friendship->update(['status' => 'accepted']);

        return $friendship;
    }

    public function denyFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestReceived()
            ->where(['sender_id' => $sender->id])->first();

        $friendship->update(['status' => 'denied']);

        return $friendship;
    }

    public function friends()
    {
        $senderFriends = $this->belongsToMany(User::class, 'friendships', 'sender_id', 'recipient_id')
            ->wherePivot('status', 'accepted')
            ->get();
        $recipientFriends = $this->belongsToMany(User::class, 'friendships', 'recipient_id', 'sender_id')
            ->wherePivot('status', 'accepted')
            ->get();
        return $senderFriends->merge($recipientFriends);
    }
}
