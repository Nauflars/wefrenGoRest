<?php
namespace App\Shared\FireFS;

use ElementaryFramework\FireFS\FireFS;
use ElementaryFramework\FireFS\Watcher\FileSystemWatcher;
use App\Shared\FireFS\WatcherListener;
use Symfony\Component\Messenger\MessageBus;

class FileWatcher {
	private MessageBus $bus;

	public function __construct(MessageBus $bus) 
	{
		$this->bus = $bus;
	}

	public function execute() 
	{
		$fs = new FireFS();
		// Check if the directory to watch exists
		if (!$fs->exists("files_to_watch")) {
		    // If not, create the directory
		    $fs->mkdir("files_to_watch");
		}
		$watcher = new FileSystemWatcher($fs);
		$watcher->setListener(new WatcherListener($this->bus))
	    ->setRecursive(true)
	    ->setPath("./files_to_watch")
	    ->setWatchInterval(250)
	    ->build(); // It's important to call build to validate the configuration
	    // Start the file watcher
		$watcher->start();
	}

	
}
