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

    /**
     * @test
     */
    public function it_wrap_mention_user_in_a_tag()
    {
        $this->signIn();

        $reply = create(Reply::class,['body' => "Hello @JaneDoe!?? @IvanPedro"]);

        $this->assertContains(
            '<a href="/profiles/JaneDoe">@JaneDoe</a>',
            $reply->body
        );

        $this->assertContains(
            '<a href="/profiles/IvanPedro">@IvanPedro</a>',
            $reply->body
        );

        $this->assertNotContains(
            '<a href="/profiles/Dbal">@NeDbal</a>',
            $reply->body
        );
    }



}
