<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 30.11.18
 * Time: 17:58
 */

namespace Tests\Unit;


use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    function test_it_records_activity_when_a_thread_is_created(){
        $this->signIn();
        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities',[
            'type' => 'creared_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id,$thread->id);
    }
}