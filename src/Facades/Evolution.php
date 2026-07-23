<?php

// src/Facades/Evolution.php

namespace Vitorfba\LaravelEvolutionClient\Facades;

use Illuminate\Support\Facades\Facade;
use Vitorfba\LaravelEvolutionClient\EvolutionApiClient;
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

/**
 * @method static EvolutionApiClient instance(string $instanceName)
 * @method static array createInstance(string $instanceName)
 * @method static array getQrCode()
 * @method static bool isConnected()
 * @method static array disconnect()
 * @method static array sendText(string $phoneNumber, string $message)
 * @method static Chat getChatAttribute()
 * @method static Group getGroupAttribute()
 * @method static Message getMessageAttribute()
 * @method static Instance getInstanceAttribute()
 * @method static Call getCallAttribute()
 * @method static Label getLabelAttribute()
 * @method static Profile getProfileAttribute()
 * @method static WebSocket getWebsocketAttribute()
 * @method static EvolutionBot getEvolutionBotAttribute()
 * @method static OpenAIBot getOpenAIBotAttribute()
 * @method static Template getTemplateAttribute()
 * @method static Proxy getProxyAttribute()
 * @method static Settings getSettingsAttribute()
 * @method static Business getBusinessAttribute()
 *
 * @see EvolutionApiClient
 */
class Evolution extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'evolution';
    }
}
