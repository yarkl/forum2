<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 04.01.19
 * Time: 17:38
 */

namespace Tests\Feature;


use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'body' => "Liverpool",
            'user_id' => auth()->id()
        ]);

        $this->assertCount(0,auth()->user()->notifications);

        $thread->addReply([
            'body' => "Liverpool",
            'user_id' => create(User::class)->id,
        ]);

        $this->assertCount(1,auth()->user()->unreadNotifications);

    }

    /** @test */
    /*public function a_notification_is_deleted_when_a_user_unsubscribe_to_thread()
    {
        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'body' => "Liverpool",
            'user_id' => create(User::class)->id,
        ]);

        $this->assertCount(1,auth()->user()->unreadNotifications);

    }*/

    /**
     * @test
     */
    public function a_user_can_fetch_their_unread_notification()
    {
        $this->signIn();

        $thread = create(Thread::class,['user_id' => 345]);

        $thread->subscribe();

        $thread->addReply([
            'body' => "fyfth",
            'user_id' => create(User::class)->id,
        ]);

        $response = $this->getJson("/profiles/". auth()->user()->name . "/notifications")->json();

        $this->assertCount(1,$response);

    }
    /**
     * @test
     */
    public function a_user_can_mark_notificatoin_as_read()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'body' => "Liverpool",
            'user_id' => create(User::class)->id,
        ]);
        $this->assertCount(1,auth()->user()->fresh()->unreadNotifications);

        $this->delete(
            "/profiles/". auth()->user()->name . "/notifications/" . auth()->user()->unreadNotifications->first()->id);

        $this->assertCount(0,auth()->user()->fresh()->unreadNotifications);

    }


}