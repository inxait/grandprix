<?php

namespace Tests\Unit;

use App\Http\Controllers\MailjetController;
use Tests\TestCase;

class MailjetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanSendMail()
    {
        $user['name'] = 'Alejandro';
        $user['identification'] = '12345678';
        $user['password'] = '2Ks9ge7M';

        $template = view('emails.activate-account', $user)->render();

        $data['user_email'] =  'alejandrogutierrezacosta@gmail.com';
        $data['user_name'] = $user['name'];
        $data['email_subject'] = 'Bienvenido a Grand Prix ACDelco, gana tus primeros km ahora';
        $data['email_description'] = 'Activación platafoma Grand Prix ACDelco';
        $data['email_template'] = $template;

        $req = MailjetController::sendEmail($data);

        echo "$req";
        $this->assertTrue($req);
    }
}
