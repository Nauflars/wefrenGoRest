<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Shared\FireFS\FileWatcher;
use Symfony\Component\Messenger\MessageBusInterface;

class FileWatcherCommand extends Command
{
    //Nombre del comando para su ejecución desde consola
    protected static $defaultName = 'app:fileWatcher';

    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
        parent::__construct();
    }
    protected function configure()
    {
      //Se configura el comando. Lo más importante es addArgument. Aunque los comandos pueden no tener argumentos o parámetos
      $this
       ->setDescription('command for File Watcher')
       ->setHelp('command for File Watcher');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
      $fileWatcher = new FileWatcher($this->bus);
      $fileWatcher->execute();
      
    }
}