<?php
/**
 * Created by PhpStorm.
 * User: yar
 * Date: 09.03.18
 * Time: 14:21
 */

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
  use DatabaseMigrations;

  public function test_a_channel_consist_of_threads(){
      $channel = create('App\Channel');
      $thread  = create('App\Thread', ['channel_id' => $channel->id]);
      $this->assertTrue($channel->threads->contains($thread));
  }
}