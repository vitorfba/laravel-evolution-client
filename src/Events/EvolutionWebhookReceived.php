<?php

namespace Vitorfba\LaravelEvolutionClient\Events;

use Vitorfba\LaravelEvolutionClient\Webhook\WebhookEvent;

/**
 * Dispatched for every inbound Evolution API webhook.
 *
 * Listen to this event to react to any Evolution event, then branch on
 * $event->webhook->name() (or the is*() helpers). A per-event named event
 * ("evolution.webhook.{name}") is also dispatched by the WebhookProcessor.
 */
class EvolutionWebhookReceived
{
    public function __construct(
        public readonly WebhookEvent $webhook
    ) {}
}
