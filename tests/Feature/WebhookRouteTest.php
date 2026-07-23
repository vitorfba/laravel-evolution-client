<?php

namespace Vitorfba\LaravelEvolutionClient\Tests\Feature;

use Vitorfba\LaravelEvolutionClient\Events\EvolutionWebhookReceived;
use Vitorfba\LaravelEvolutionClient\Jobs\ProcessEvolutionWebhook;
use Vitorfba\LaravelEvolutionClient\Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;

class WebhookRouteTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('evolution.webhook.route.enabled', true);
        $app['config']->set('evolution.webhook.route.path', 'evolution/webhook');
        $app['config']->set('evolution.webhook.route.middleware', []);
    }

    /** @test */
    public function it_receives_a_webhook_and_dispatches_events_inline()
    {
        Event::fake([EvolutionWebhookReceived::class]);

        $response = $this->postJson('evolution/webhook', [
            'event' => 'messages.upsert',
            'instance' => 'sales',
            'data' => ['key' => ['id' => 'ABC']],
        ]);

        $response->assertOk()->assertJson([
            'status' => 'received',
            'event' => 'messages.upsert',
        ]);

        Event::assertDispatched(
            EvolutionWebhookReceived::class,
            fn (EvolutionWebhookReceived $e) => $e->webhook->name() === 'messages.upsert'
                && $e->webhook->instance === 'sales'
        );
    }

    /** @test */
    public function it_queues_processing_when_the_queue_is_enabled()
    {
        config()->set('evolution.webhook.queue.enabled', true);
        Bus::fake();

        $response = $this->postJson('evolution/webhook', [
            'event' => 'messages.upsert',
            'data' => [],
        ]);

        $response->assertOk()->assertJson(['status' => 'queued']);

        Bus::assertDispatched(ProcessEvolutionWebhook::class);
    }
}
