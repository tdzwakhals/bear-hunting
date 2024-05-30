<?php

declare(strict_types=1);

namespace App\EventSubscriber\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final readonly class RateLimiterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RateLimiterFactory $anonymousApiLimiter,
        private RequestStack $requestStack,
        private RateLimiterFactory $authenticatedApiLimiter,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated',
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $this->validateLimiter(
            $this->anonymousApiLimiter->create(
                $this->requestStack->getCurrentRequest()->getClientIp()
            )
        );
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->validateLimiter(
            $this->authenticatedApiLimiter->create($event->getRequest()->getClientIp())
        );
    }

    private function validateLimiter(LimiterInterface $limiter): void
    {
        if($limiter->consume()->isAccepted() === false) {
            throw new TooManyRequestsHttpException();
        }
    }
}