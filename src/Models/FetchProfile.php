<?php

namespace Vitorfba\LaravelEvolutionClient\Models;

class FetchProfile extends Profile
{
    /**
     * Create a new FetchProfile instance.
     */
    public function __construct(string $number)
    {
        parent::__construct(['number' => $number]);
    }
}
