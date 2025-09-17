<?php

$factory = new \App\Dto\Form\FormInputFactory();

$auth = [
    $factory->input('login', $factory->nonErrorTip('U will be registered if u are new')),
    $factory->password('pass', 'Password'),
];

?><x-layout.main>
    <x-layout.header-main>Eternity creation Engine</x-layout.header-main>
    <x-form.basic :route="route('web-auth')" btn="Allod-Ulaz" :fields="$auth"></x-form.basic>
</x-layout.main>
