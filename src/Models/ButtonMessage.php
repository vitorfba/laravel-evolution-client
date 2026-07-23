<?php

// src/Models/ButtonMessage.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class ButtonMessage
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new ButtonMessage instance.
     */
    public function __construct(
        string $number,
        string $title,
        string $description,
        string $footer,
        array $buttons,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $buttonsArray = [];

        foreach ($buttons as $button) {
            if ($button instanceof Button) {
                $buttonsArray[] = $button->toArray();
            } else {
                $buttonsArray[] = $button;
            }
        }

        $this->attributes = [
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'footer' => $footer,
            'buttons' => $buttonsArray,
        ];

        if ($delay !== null) {
            $this->attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $this->attributes['quoted'] = $quoted->toArray();
        }
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
