<?php

namespace App\Mail;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Illuminate\Events\Dispatcher;

class SymfonyEventDispatcherAdapter implements EventDispatcherInterface
{
    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        if ($event instanceof Event) {
            $this->dispatcher->dispatch($eventName ?? get_class($event), $event);
        }
        return $event;
    }
} 