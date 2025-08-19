<?php

namespace App\Mail;



use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;



class NotificationMail extends Mailable

{

    use Queueable, SerializesModels;



    public $title;

    public $body;

    public $org;

    public $user;




    public function __construct($title, $message, $org, $user)

    {

        $this->title = $title;

    $this->body = $message;

        $this->org = $org;

        $this->user = $user;


    }



    public function build()

    {

        return $this->subject($this->title)

            ->view('emails.notification_mail')

            ->with([

                'title' => $this->title,

                'body' => $this->body,

                'org' => $this->org,

                'user' => $this->user,

            ]);



    }

}

