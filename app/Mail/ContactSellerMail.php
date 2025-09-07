<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSellerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $messageContent;
    public $productName;

    public function __construct($name, $email, $messageContent, $productName)
    {
        $this->name = $name;
        $this->email = $email;
        $this->messageContent = $messageContent;
        $this->productName = $productName;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo($this->email)
                    ->subject('Official Message from TradeVista Hub – Product: ' . $this->productName)
                    ->view('emails.contact-seller');
    }
}
