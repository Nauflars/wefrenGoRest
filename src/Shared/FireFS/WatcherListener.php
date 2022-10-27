<?php

namespace App\Shared\FireFS;

// We use the IFileSystemWatcher interface
use ElementaryFramework\FireFS\Listener\IFileSystemListener;

// We use the FileSystemEvent class
use ElementaryFramework\FireFS\Events\FileSystemEvent;
use Symfony\Component\Messenger\MessageBus;
use App\Shared\Message\SmsNotification;

// Our listener
class WatcherListener implements IFileSystemListener
{
    private MessageBus $bus;

    public function __construct(MessageBus $bus) 
    {
        $this->bus = $bus;
    }

    /**
     * Action executed on any event.
     * The returned boolean will define if the
     * specific event handler (onCreated, onModified, onDeleted)
     * have to be called after this call.
     */
    public function onAny(FileSystemEvent $event): bool
    {
        $eventType = $event->getEventType();
        $date = date("d/m/Y H:i:s");

        if ($eventType === FileSystemEvent::EVENT_UNKNOWN) {
            return true;
        }

        switch ($eventType) {
            case FileSystemEvent::EVENT_CREATE:
                $this->onCreated($event);
                break;

            case FileSystemEvent::EVENT_MODIFY:
                $this->onModified($event);
                break;

            case FileSystemEvent::EVENT_DELETE:
                $this->onDeleted($event);
                break;
        }

        return false;
    }

    /**
     * Action executed when a file/folder is created.
     */
    public function onCreated(FileSystemEvent $event)
    {
         print "dispatch {$event->getPath()}\n";
        $this->bus->dispatch(new SmsNotification("{$event->getPath()}"));
    }

    /**
     * Action executed when a file/folder is updated.
     */
    public function onModified(FileSystemEvent $event)
    {
     }

    /**
     * Action executed when a file/folder is deleted.
     */
    public function onDeleted(FileSystemEvent $event)
    { 
    }
}