<?php

// src/Models/Proxy.php

namespace Happones\LaravelEvolutionClient\Models;

class Proxy
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * Create a new Proxy instance.
     */
    public function __construct(
        bool $enabled,
        string $host,
        string $port,
        string $protocol,
        ?string $username = null,
        ?string $password = null
    ) {
        $this->enabled = $enabled;
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $result = [
            'enabled' => $this->enabled,
            'host' => $this->host,
            'port' => $this->port,
            'protocol' => $this->protocol,
        ];

        if ($this->username !== null) {
            $result['username'] = $this->username;
        }

        if ($this->password !== null) {
            $result['password'] = $this->password;
        }

        return $result;
    }
}
