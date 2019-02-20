<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 20.02.19
 * Time: 20:12
 */

namespace Tests\Feature;


use Faker\Provider\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function only_auth_users_can_upload_an_avatar()
    {
        $this->withExceptionHandling();

        $this->postJson('/api/users/1/avatar')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_validates_an_image()
    {
        $this->withExceptionHandling()->signIn();

        $this->postJson('/api/users/'. auth()->user()->name .'/avatar',[
            'avatar' => 'not an avatar'
        ])->assertStatus(422);
    }

    /**
     * @test
     */
    public function auth_users_can_upload_an_avatar()
    {
        $this->signIn();

        Storage::fake('public');

        $this->postJson('/api/users/'. auth()->user()->name .'/avatar',[
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])->assertStatus(200);

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());

    }
}