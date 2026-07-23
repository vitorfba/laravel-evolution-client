<?php

namespace Vitorfba\LaravelEvolutionClient\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vitorfba\LaravelEvolutionClient\Webhook\WebhookEvent;
use Vitorfba\LaravelEvolutionClient\Webhook\WebhookProcessor;

/**
 * Processes a single Evolution webhook payload off the request lifecycle.
 *
 * Dispatched by WebhookController when config('evolution.webhook.queue.enabled')
 * is true. Re-runs the payload through the WebhookProcessor on the worker, which
 * dispatches EvolutionWebhookReceived and the named "evolution.webhook.{event}"
 * event. Inline callbacks registered on the singleton do not survive the queue
 * boundary; use event listeners for queued handling.
 */
class ProcessEvolutionWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, mixed> $payload the raw webhook payload
     */
    public function __construct(
        public readonly array $payload
    ) {}

    public function handle(WebhookProcessor $processor): WebhookEvent
    {
        return $processor->process($this->payload);
    }
}
