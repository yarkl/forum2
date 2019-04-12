<?php
/**
 * Created by PhpStorm.
 * User: yar
 * Date: 10.03.18
 * Time: 20:20
 */

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_cant_favorite_a_reply(){
        $this->withExceptionHandling()
        ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    public function test_an_auth_user_can_favorite_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post('/replies/' . $reply->id .'/favorites');
        $this->assertCount(1,$reply->favorites);
    }

    public function test_an_auth_user_can_favorite_a_reply_once(){
        $this->signIn();
        $reply = create('App\Reply');

        try {
            $this->post('/replies/' . $reply->id .'/favorites');

            $this->post('/replies/' . $reply->id .'/favorites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert two records');
        }

        $this->assertCount(1,$reply->favorites);
    }

}