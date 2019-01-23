<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ReplyTest extends TestCase
{
    use DatabaseMigrations;


    public function test_it_has_an_owner()
    {
         $reply = factory('App\Reply')->create();
         $this->assertInstanceOf('App\User', $reply->owner);
    }


    public function test_it_know_that_it_has_just_published()
    {
        $reply = create(Reply::class);
        $this->assertTrue($reply->wasJustPublished());
    }



}
