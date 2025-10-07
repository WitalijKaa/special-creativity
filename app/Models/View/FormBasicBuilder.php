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
    public array $thirdColumn = [];
    private int $column = 1;

    public string $method = 'POST';
    public string $route;
    public string $label;

    public function formPrepend(FormFieldInputDto|array $elem): self
    {
        $elem = is_array($elem) ? $elem : [$elem];
        $model = new static();
        $model->mainColumn = array_merge($elem, $this->mainColumn);
        $model->secondaryColumn = $this->secondaryColumn;
        $model->thirdColumn = $this->thirdColumn;
        $model->method = $this->method;
        if (!empty($this->route)) {
            $model->route = $this->route;
            $model->label = $this->label;
        }
        return $model;
    }

    public function add(FormFieldInputDto $elem): self
    {
        if (1 == $this->column) {
            $this->mainColumn[] = $elem;
        } else if (2 == $this->column)  {
            $this->secondaryColumn[] = $elem;
        } else if (3 == $this->column)  {
            $this->thirdColumn[] = $elem;
        }
        return $this;
    }

    public function firstColumn(?FormFieldInputDto $elem = null): self
    {
        $this->column = 1;
        if ($elem) { $this->add($elem); }
        return $this;
    }

    public function secondColumn(?FormFieldInputDto $elem = null): self
    {
        $this->column = 2;
        if ($elem) { $this->add($elem); }
        return $this;
    }

    public function thirdColumn(?FormFieldInputDto $elem = null): self
    {
        $this->column = 3;
        if ($elem) { $this->add($elem); }
        return $this;
    }

    public function route(string $route, ?string $label = null): self
    {
        $this->route = $route;
        $this->label = empty($this->label) && empty($label) ? 'Submit' :
            (!empty($label) ? $label : $this->label);
        return $this;
    }

    public function singleTextareaAtSecondColumnHeight(): ?int
    {
        if (count($this->secondaryColumn) != 1 ||
            count($this->thirdColumn) ||
            count($this->mainColumn) <= 2 ||
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
