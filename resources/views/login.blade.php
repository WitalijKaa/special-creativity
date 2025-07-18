<?php

$fLogin = new \App\Dto\Form\FormFieldInputDto();
$fLogin->id = 'login';
$fLogin->label = 'Login';
$fLogin->nonErrorTip = 'U will be registered if u are new';
$fPass = new \App\Dto\Form\FormFieldInputDto();
$fPass->id = 'pass';
$fPass->label = 'Password';
$fPass->type = 'password';

?><x-layout.main>
    <header class="container text-center">
        <h1>Main page</h1>
        <hr class="border border-danger border-2">
    </header>

    <x-form.basic :route="route('web-auth')" btn="Allod-Ulaz" :fields="[$fLogin, $fPass]"></x-form.basic>
</x-layout.main>
