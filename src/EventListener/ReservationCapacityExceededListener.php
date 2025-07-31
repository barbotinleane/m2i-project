<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\Event\PostSubmitEvent;

final class ReservationCapacityExceededListener
{
    #[AsEventListener]
    public function onPostSubmitEvent(PostSubmitEvent $event): void
    {
        // ...
    }
}
