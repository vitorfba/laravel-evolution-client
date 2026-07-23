<?php

// src/EvolutionApiClient.php

namespace Vitorfba\LaravelEvolutionClient;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Resources\Business;
use Vitorfba\LaravelEvolutionClient\Resources\Call;
use Vitorfba\LaravelEvolutionClient\Resources\Chat;
use Vitorfba\LaravelEvolutionClient\Resources\EvolutionBot;
use Vitorfba\LaravelEvolutionClient\Resources\Group;
use Vitorfba\LaravelEvolutionClient\Resources\Instance;
use Vitorfba\LaravelEvolutionClient\Resources\Label;
use Vitorfba\LaravelEvolutionClient\Resources\Message;
use Vitorfba\LaravelEvolutionClient\Resources\OpenAIBot;
use Vitorfba\LaravelEvolutionClient\Resources\Profile;
use Vitorfba\LaravelEvolutionClient\Resources\Proxy;
use Vitorfba\LaravelEvolutionClient\Resources\Settings;
use Vitorfba\LaravelEvolutionClient\Resources\Template;
use Vitorfba\LaravelEvolutionClient\Resources\WebSocket;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class EvolutionApiClient
{
    /**
     * @var Chat The Chat resource
     */
    public Chat $chat;

    /**
     * @var Group The Group resource
     */
    public Group $group;

    /**
     * @var Message The Message resource
     */
    public Message $message;

    /**
     * @var Instance The Instance resource
     */
    public Instance $instance;

    /**
     * @var Call The Call resource
     */
    public Call $call;

    /**
     * @var Label The Label resource
     */
    public Label $label;

    /**
     * @var Profile The Profile resource
     */
    public Profile $profile;

    /**
     * @var WebSocket The WebSocket resource
     */
    public WebSocket $websocket;

    /**
     * @var string The instance name
     */
    protected string $instanceName;

    /**
     * @var EvolutionService The Evolution API service
     */
    protected EvolutionService $service;

    /**
     * @var Template The Template resource
     */
    public Template $template;

    /**
     * @var Proxy The Proxy resource
     */
    public Proxy $proxy;

    /**
     * @var Settings The Settings resource
     */
    public Settings $settings;

    /**
     * @var OpenAIBot The OpenAIBot resource
     */
    public OpenAIBot $openAIBot;

    /**
     * @var EvolutionBot The EvolutionBot resource
     */
    public EvolutionBot $evolutionBot;

    /**
     * @var Business The Business resource
     */
    public Business $business;

    /**
     * Create a new EvolutionApiClient instance.
     */
    public function __construct(EvolutionService $service, string $instanceName = 'default')
    {
        $this->service = $service;
        $this->instanceName = $instanceName;

        // Initialize resources
        $this->chat = new Chat($service, $instanceName);
        $this->group = new Group($service, $instanceName);
        $this->message = new Message($service, $instanceName);
        $this->instance = new Instance($service, $instanceName);
        $this->call = new Call($service, $instanceName);
        $this->label = new Label($service, $instanceName);
        $this->profile = new Profile($service, $instanceName);
        $this->websocket = new WebSocket($service, $instanceName);
        $this->template = new Template($service, $instanceName);
        $this->proxy = new Proxy($service, $instanceName);
        $this->settings = new Settings($service, $instanceName);
        $this->openAIBot = new OpenAIBot($service, $instanceName);
        $this->evolutionBot = new EvolutionBot($service, $instanceName);
        $this->business = new Business($service, $instanceName);
    }

    /**
     * Set the instance name.
     */
    public function instance(string $instanceName): self
    {
        $this->instanceName = $instanceName;

        // Update instance name in all resources
        $this->chat->setInstanceName($instanceName);
        $this->group->setInstanceName($instanceName);
        $this->message->setInstanceName($instanceName);
        $this->instance->setInstanceName($instanceName);
        $this->call->setInstanceName($instanceName);
        $this->label->setInstanceName($instanceName);
        $this->profile->setInstanceName($instanceName);
        $this->websocket->setInstanceName($instanceName);
        $this->template->setInstanceName($instanceName);
        $this->proxy->setInstanceName($instanceName);
        $this->settings->setInstanceName($instanceName);
        $this->evolutionBot->setInstanceName($instanceName);
        $this->openAIBot->setInstanceName($instanceName);
        $this->business->setInstanceName($instanceName);

        return $this;
    }

    /**
     * Get the QR code for the instance.
     *
     *
     * @throws EvolutionApiException
     */
    public function createInstance(string $name): array
    {
        $this->instance->setInstanceName($name);

        return $this->instance->createInstance($name);
    }

    /**
     * Get the QR code for the instance.
     *
     * @throws EvolutionApiException
     */
    public function getQrCode(): array
    {
        return $this->instance->getQrCode();
    }

    /**
     * Check if the instance is connected.
     *
     * @throws EvolutionApiException
     */
    public function isConnected(): bool
    {
        return $this->instance->isConnected();
    }

    /**
     * Disconnect the instance.
     *
     * @throws EvolutionApiException
     */
    public function disconnect(): array
    {
        return $this->instance->disconnect();
    }

    /**
     * Send a text message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendText(string $phoneNumber, string $message): array
    {
        return $this->message->sendText($phoneNumber, $message);
    }
}
