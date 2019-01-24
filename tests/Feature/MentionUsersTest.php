<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 24.01.19
 * Time: 12:10
 */

namespace Tests\Feature;


use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_can_mention_another_user()
    {
        $user1 = create(User::class,['name' => 'User1']);

        $this->signIn($user1);

        $user2 = create(User::class,['name' => 'User2']);

        $thread = create(Thread::class);

        $reply = make(Reply::class,['body' => "Hello @{$user2->name}"]);

        $this->json('POST' ,$thread->path() . '/replies',$reply->toArray());

        $this->assertCount(1,$user2->notifications);
    }
}