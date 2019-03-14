<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 14.03.19
 * Time: 8:13
 */

namespace Tests\Feature;


use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ThreadsVisitsCountTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */

    public function it_records_thread_visits()
    {
        $thread = make(Thread::class);

        Redis::del("threads.{$thread->id}.visits");

        $this->assertEquals(0,$thread->visits());

        $thread->recordVisits();

        $this->assertEquals(1,$thread->visits());
    }
}