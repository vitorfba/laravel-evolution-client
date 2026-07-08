<?php

namespace Happones\LaravelEvolutionClient\Webhook;

use Happones\LaravelEvolutionClient\Events\EvolutionWebhookReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

/**
 * Parses inbound Evolution API webhook payloads and exposes them for use.
 *
 * Two consumption styles are supported:
 *   - Laravel events: process() dispatches EvolutionWebhookReceived plus a named
 *     event "evolution.webhook.{event}" (e.g. "evolution.webhook.messages.upsert").
 *   - Inline callbacks: register handlers with on()/onAny() and they run on process().
 */
class WebhookProcessor
{
    /**
     * Registered per-event callbacks, keyed by normalised event name.
     *
     * @var array<string, list<callable>>
     */
    protected array $handlers = [];

    /**
     * Callbacks invoked for every event.
     *
     * @var list<callable>
     */
    protected array $anyHandlers = [];

    public function __construct(
        protected ?Dispatcher $events = null
    ) {}

    /**
     * Register a callback for a specific event name.
     */
    public function on(string $event, callable $handler): self
    {
        $this->handlers[WebhookEvent::normalizeEventName($event)][] = $handler;

        return $this;
    }

    /**
     * Register a callback invoked for every event.
     */
    public function onAny(callable $handler): self
    {
        $this->anyHandlers[] = $handler;

        return $this;
    }

    /**
     * Parse a raw payload into a WebhookEvent (no dispatching).
     *
     * @param array<string, mixed> $payload
     */
    public function parse(array $payload): WebhookEvent
    {
        return WebhookEvent::fromArray($payload);
    }

    /**
     * Parse the JSON body of an incoming HTTP request into a WebhookEvent.
     */
    public function fromRequest(Request $request): WebhookEvent
    {
        return $this->parse($request->all());
    }

    /**
     * Parse, dispatch events, run callbacks, and return the WebhookEvent.
     *
     * @param Request|array<string, mixed> $payload
     */
    public function process(Request|array $payload): WebhookEvent
    {
        $event = $payload instanceof Request
            ? $this->fromRequest($payload)
            : $this->parse($payload);

        foreach ($this->anyHandlers as $handler) {
            $handler($event);
        }

        foreach ($this->handlers[$event->name()] ?? [] as $handler) {
            $handler($event);
        }

        if ($this->events !== null) {
            $this->events->dispatch(new EvolutionWebhookReceived($event));
            $this->events->dispatch('evolution.webhook.' . $event->name(), [$event]);
        }

        return $event;
    }
}
