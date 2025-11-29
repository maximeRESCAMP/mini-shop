<?php

namespace App\EventSubscriber;

use App\Exception\Address\AddressAlreadyExistsException;
use App\Exception\Address\AddressUserNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $router)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $session = $event->getRequest()->getSession();
        if ($e instanceof AddressAlreadyExistsException) {
            $session->getFlashBag()->add('danger', $e->getMessage());
            $url = $this->router->generate('app_address_list');
            $event->setResponse(new RedirectResponse($url));
        }
        if ($e instanceof AddressUserNotFoundException) {
            $session->getFlashBag()->add('danger', $e->getMessage());
            $url = $this->router->generate('app_address_add');
            $event->setResponse(new RedirectResponse($url));
        }

    }


}
