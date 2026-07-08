<?php

namespace Happones\LaravelEvolutionClient\Models;

class ProfilePicture extends Profile
{
    /**
     * Create a new ProfilePicture instance.
     */
    public function __construct(string $picture)
    {
        parent::__construct(['picture' => $picture]);
    }
}
