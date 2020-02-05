<?php

namespace App\EventSubscriber;
use App\Entity\PubishedDateEntityInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class PublishedDateEntitySubscriber implements EventSubscriberInterface
{


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_WRITE]
        ];
    }

    public function  setDatePublished(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if((!$entity instanceof PubishedDateEntityInterface) || Request::METHOD_POST != $method){
            return;
        }

        $entity->setPublished(new \DateTime());
    }

}

?>