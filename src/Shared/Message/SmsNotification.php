<?php 
// src/Shared/Message/SmsNotification.php
namespace App\Shared\Message;

class SmsNotification
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}