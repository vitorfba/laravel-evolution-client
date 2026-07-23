<?php

// src/Models/WebSocket.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class WebSocket
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var array
     */
    protected $events;

    /**
     * Create a new WebSocket instance.
     */
    public function __construct(bool $enabled, array $events = [])
    {
        $this->enabled = $enabled;
        $this->events = $events;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'events' => $this->events,
        ];
    }
}
