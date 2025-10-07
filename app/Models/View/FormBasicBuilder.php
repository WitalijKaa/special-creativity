<?php

namespace App\Models\View;

use App\Dto\Form\FormFieldInputDto;
use App\Dto\Form\FormInputFactory;

class FormBasicBuilder
{
    /** @var array<FormFieldInputDto> */
    public array $mainColumn = [];
    /** @var array<FormFieldInputDto> */
    public array $secondaryColumn = [];
    private int $column = 1;

    public string $method = 'POST';
    public string $route;
    public string $label;

    public function add(FormFieldInputDto $elem): self
    {
        if (1 == $this->column) {
            $this->mainColumn[] = $elem;
        } else if (2 == $this->column)  {
            $this->secondaryColumn[] = $elem;
        }
        return $this;
    }

    public function firstColumn(?FormFieldInputDto $elem = null): self
    {
        $this->column = 2;
        if ($elem) { $this->add($elem); }
        return $this;
    }

    public function secondColumn(?FormFieldInputDto $elem = null): self
    {
        $this->column = 2;
        if ($elem) { $this->add($elem); }
        return $this;
    }

    public function route(string $route, string $label = 'Submit'): self
    {
        $this->route = $route;
        $this->label = $label;
        return $this;
    }

    public function singleTextareaAtSecondColumnHeight(): ?int
    {
        if (count($this->secondaryColumn) != 1 ||
            $this->secondaryColumn[0]->type != FormInputFactory::T_TEXTAREA ||
            !$this->hasNoTextareaAtMainColumn()
        ) {
            return null;
        }
        return count($this->mainColumn) * 3;
    }

    private function hasNoTextareaAtMainColumn(): bool
    {
        return array_all($this->mainColumn, fn($elem) => $elem->type != FormInputFactory::T_TEXTAREA);
    }
}
