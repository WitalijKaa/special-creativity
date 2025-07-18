<?php

namespace App\Dto\Form;

class FormFieldInputDto
{
    public ?string $value = null;

    public string $id;
    public string $type = 'text';
    public string $label;
    public ?string $nonErrorTip = null;
}
