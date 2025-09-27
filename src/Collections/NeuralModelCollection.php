<?php

namespace LLMSpeak\Core\Collections;

use Illuminate\Support\Collection;
use LLMSpeak\Core\NeuralModels\NeuralModel;

class NeuralModelCollection extends Collection
{
    /**
     * @param<NeuralModel> $items
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }
}
