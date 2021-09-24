<?php

use App\Events\StatusCreated;
use App\Http\Controllers\CommentLikesController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\FriendshipsController;
use App\Http\Controllers\AcceptFriendshipsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReadNotificationController;
use App\Http\Controllers\StatusCommentsController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\StatusLikesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersStatusController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/', 'welcome');

// Statuses routes
Route::get('statuses', [StatusesController::class, 'index'])->name('statuses.index');
Route::get('statuses/{status}', [StatusesController::class, 'show'])->name('statuses.show');
Route::post('statuses', [StatusesController::class, 'store'])->name('statuses.store')->middleware('auth');

// Statuses likes routes
Route::post('statuses/{status}/likes', [StatusLikesController::class, 'store'])->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes', [StatusLikesController::class, 'destroy'])->name('statuses.likes.destroy')->middleware('auth');

// Statuses comments routes
Route::post('statuses/{status}/comments', [StatusCommentsController::class, 'store'])->name('statuses.comments.store')->middleware('auth');

// Comments likes routes
Route::post('comments/{comment}/likes', [CommentLikesController::class, 'store'])->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes', [CommentLikesController::class, 'destroy'])->name('comments.likes.destroy')->middleware('auth');

// Users routes
Route::get('@{user}', [UserController::class, 'show'])->name('users.show');

// Users status routes
Route::get('users/{user}/statuses', [UsersStatusController::class, 'index'])->name('users.statuses.index');

// Friends routes
Route::get('friends', [FriendsController::class, 'index'])->name('friends.index')->middleware('auth');

// Friendship routes
Route::get('friendships/{recipient}', [FriendshipsController::class,'show'])->name('friendships.show')->middleware('auth');
Route::post('friendships/{recipient}', [FriendshipsController::class, 'store'])->name('friendship.store')->middleware('auth');
Route::delete('friendships/{user}', [FriendshipsController::class, 'destroy'])->name('friendship.destroy')->middleware('auth');

// Accept friendships routes
Route::get('friends/requests', [AcceptFriendshipsController::class, 'index'])->name('accept-friendships.index')->middleware('auth');
Route::post('accept-friendships/{sender}', [AcceptFriendshipsController::class, 'store'])->name('accept-friendships.store')->middleware('auth');
Route::delete('accept-friendships/{sender}', [AcceptFriendshipsController::class, 'destroy'])->name('accept-friendships.destroy')->middleware('auth');

// Notification routes
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');;

// Read Notification routes
Route::post('read-notifications/{notification}', [ReadNotificationController::class, 'store'])->name('read-notifications.store')->middleware('auth');
Route::delete('read-notifications/{notification}', [ReadNotificationController::class, 'destroy'])->name('read-notifications.destroy')->middleware('auth');
