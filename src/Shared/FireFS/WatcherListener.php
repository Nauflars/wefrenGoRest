<?php

namespace App\Shared\FireFS;

// We use the IFileSystemWatcher interface
use ElementaryFramework\FireFS\Listener\IFileSystemListener;

// We use the FileSystemEvent class
use ElementaryFramework\FireFS\Events\FileSystemEvent;

// Our listener
class WatcherListener implements IFileSystemListener
{
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
                print "{$date}  -  [Created]  {$event->getPath()}\n";
                break;

            case FileSystemEvent::EVENT_MODIFY:
                print "{$date}  -  [Updated]  {$event->getPath()}\n";
                break;

            case FileSystemEvent::EVENT_DELETE:
                print "{$date}  -  [Deleted]  {$event->getPath()}\n";
                break;
        }

        return false;
    }

    /**
     * Action executed when a file/folder is created.
     */
    public function onCreated(FileSystemEvent $event)
    { }

    /**
     * Action executed when a file/folder is updated.
     */
    public function onModified(FileSystemEvent $event)
    { }

    /**
     * Action executed when a file/folder is deleted.
     */
    public function onDeleted(FileSystemEvent $event)
    { }
}