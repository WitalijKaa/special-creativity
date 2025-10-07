<?php

namespace App\Dto\Form;

class FormInputFactory
{
    private ?FormFieldInputDto $preDefined;

    public const string T_TEXTAREA = 'textarea';

    public function input(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        if ($label instanceof FormInputFactory) {
            $dto = $label->flashPreDefined();
            $label = null;
        }
        else if ($preDefined instanceof FormInputFactory) {
            $dto = $preDefined->flashPreDefined();
        }
        $dto = empty($dto) ? new FormFieldInputDto() : $dto;
        $dto->id = $id;
        $dto->label = $label ?? ucfirst($id);
        return $dto;
    }

    public function password(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->type = 'password';
        return $dto;
    }

    public function checkbox(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->type = 'checkbox';
        return $dto;
    }

    public function number(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->type = 'number';
        return $dto;
    }

    public function hidden(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->type = 'hidden';
        return $dto;
    }

    public function textarea(string $id, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->type = self::T_TEXTAREA;
        return $dto;
    }

    public function select(string $id, array $options, null|string|self $label = null, null|self $preDefined = null): FormFieldInputDto
    {
        $dto = $this->input($id, $label, $preDefined);
        $dto->options = $options;
        return $dto;
    }

    private function flashPreDefined(): ?FormFieldInputDto
    {
        $return = $this->preDefined;
        $this->preDefined = null;
        return $return;
    }

    public function nonErrorTip(string $tip): static
    {
        if (empty($this->preDefined)) {
            $this->preDefined = new FormFieldInputDto();
        }
        $this->preDefined->nonErrorTip = $tip;
        return $this;
    }

    public function withValue($value): static
    {
        if (empty($this->preDefined)) {
            $this->preDefined = new FormFieldInputDto();
        }
        $this->preDefined->value = $value;
        return $this;
    }
}
