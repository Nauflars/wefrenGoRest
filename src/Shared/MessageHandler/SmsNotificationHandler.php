<?php 
// src/MessageHandler/SmsNotificationHandler.php
namespace App\Shared\MessageHandler;

use App\Shared\Message\SmsNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use ElementaryFramework\FireFS\FireFS;

class SmsNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(SmsNotification $message)
    {
      $pathOrigen = $message->getContent();
      $dirname = pathinfo($pathOrigen, PATHINFO_DIRNAME);
      $nameOrigen = basename($pathOrigen).PHP_EOL;

      $fs = new FireFS();
      if (!$fs->exists("destination"))
      {
          $fs->mkdir("destination");
      }
      print "{$pathOrigen}\n";
      print "{$dirname}/destination/{$nameOrigen}\n";
      $fs->move($pathOrigen, $dirname."\destination\\".$nameOrigen);
    }
}