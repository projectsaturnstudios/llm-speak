<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait StructuredOutput
{
    protected ?array $output_format = null;

    public function setOutputFormat(?array $output_format): static
    {
        $this->output_format = $output_format;
        return $this->addToOriginal("output_format", $output_format);
    }

    public function outputFormat(): ?array
    {
        return $this->output_format;
    }


}
