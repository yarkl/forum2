<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 12.04.19
 * Time: 9:12
 */

namespace Tests\Feature;


use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockedThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_administrator_can_lock_a_thread()
    {
        $thread = create(Thread::class);

        $thread->lock();

        $this->signIn();

        $this->post($thread->path() . '/replies', [
            'body' => 'Tralala',
        ])->assertStatus(422);


    }
}