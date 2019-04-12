<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 21.03.19
 * Time: 8:30
 */

namespace Tests\Feature;


use App\Mail\ConfirmationEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_registration()
    {
        Mail::fake();

        $this->post('/register',[
            'name' => 'Fake',
            'email' => 'email@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('Fake')->first();

        $this->assertNull($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get('/registration-confirm/' . $user->confirmation_token);

        $this->assertEquals(1,$user->fresh()->confirmed);

        $this->assertEquals('',$user->fresh()->confirmation_token);
    }


    /**
     * @test
     */
    public function a_user_must_receive_an_email_during_sign_up()
    {
        Mail::fake();

        event(new Registered(User::create([
            'name' => 'Eing',
            'email' => 'zwei@gmail.com',
            'password' => bcrypt('password'),
        ])));

        Mail::assertSent(ConfirmationEmail::class);
    }

    /**
     * @test
     */
    public function a_user_must_have_confirmation_token_during_sign_up()
    {
        $user = create(User::class);

        $this->assertNotNull($user->confirmation_token);
    }
}