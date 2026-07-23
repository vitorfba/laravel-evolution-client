<?php

namespace Vitorfba\LaravelEvolutionClient\Http\Controllers;

use Vitorfba\LaravelEvolutionClient\Jobs\ProcessEvolutionWebhook;
use Vitorfba\LaravelEvolutionClient\Webhook\WebhookEvent;
use Vitorfba\LaravelEvolutionClient\Webhook\WebhookProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ready-made endpoint for receiving Evolution API webhooks.
 *
 * Enable it via config('evolution.webhook.route.enabled'). It parses the payload,
 * dispatches the corresponding events (inline, or on a queue when
 * config('evolution.webhook.queue.enabled') is true), and returns 200 so
 * Evolution stops retrying.
 */
class WebhookController
{
    public function __invoke(Request $request, WebhookProcessor $processor): JsonResponse
    {
        if (config('evolution.webhook.queue.enabled')) {
            $payload = $request->all();

            ProcessEvolutionWebhook::dispatch($payload)
                ->onConnection(config('evolution.webhook.queue.connection'))
                ->onQueue(config('evolution.webhook.queue.queue'));

            return new JsonResponse([
                'status' => 'queued',
                'event' => WebhookEvent::fromArray($payload)->name(),
            ]);
        }

        $event = $processor->process($request);

        return new JsonResponse([
            'status' => 'received',
            'event' => $event->name(),
        ]);
    }
}
