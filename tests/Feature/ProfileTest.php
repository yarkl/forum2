<?php
/**
 * Created by PhpStorm.
 * User: yar
 * Date: 19.03.18
 * Time: 13:15
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
  use DatabaseMigrations;

  public function test_a_user_has_profile()
  {
      $user = create('App\User');
      $this->get('/profiles/'.$user->name )
          ->assertSee($user->name);
  }


  public function test_profile_display_all_threads_assoc_with_a_user(){
      $user = create('App\User');
      $thread = create('App\Thread',['user_id' => $user->id]);
      $this->get('/profiles/'.$user->name )
          ->assertSee($thread->title)
          ->assertSee($thread->body);

  }
}