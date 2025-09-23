<?php

$factory = new \App\Dto\Form\FormInputFactory();

$auth = [
    $factory->input('login', $factory->nonErrorTip('U will be registered if u are new')),
    $factory->password('pass', 'Password'),
];

?><x-layout.main>
    <div style="height: 5vh;"></div>
    <x-layout.header-main>Eternity creation Engine</x-layout.header-main>
    <x-form.basic :route="route('web-auth')" btn="Allod-Entrance" :fields="$auth"></x-form.basic>

    <x-layout.container>
        <hr class="border border-danger border-4">

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ruski</h4>
                        <p class="card-text">вы писатель и решили писать произведение очень специфического жанра. это фантастика о заселении пустого дикого мира.</p>
                        <p class="card-text">условия такие: вы представитель высокоразвитой человеческой цивилизации и прилетели в дальний уголок космоса один. чтоб преодолевать необъятные космические пространства ваша цивилизация изобрела технологию призрачных перемещений. вы призрак.</p>
                        <p class="card-text">но вы способны вселяться в тела гуманоидной дикой неразумной расы, что живёт на поверхности планеты, колонизируя её</p>
                        <p class="card-text">в зависимости от успехов колонизации вы сможете вызвать на планету больше разумных существ, что также как вы смогут жить вселяясь в тела и набравшись сил вызывать сюда еще людей</p>
                        <p class="card-text">данный калькулятор позволит вам вести подсчёт прогресса колонизации, записывать хитросплетения отношений между людьми что живут множество жизней</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Engleski</h4>
                        <p class="card-text">You are a writer and have decided to create a work in a very specific genre. This is science fiction about the colonization of a wild, empty world.</p>
                        <p class="card-text">The premise is this: you are a representative of a highly developed human civilization and have arrived alone in a distant corner of the cosmos. To cross the vast cosmic distances, your civilization invented the technology of ghostly travel. You are a ghost.</p>
                        <p class="card-text">However, you are able to inhabit the bodies of a humanoid, primitive, mindless race that lives on the planet’s surface, thereby colonizing it.</p>
                        <p class="card-text">Depending on the success of colonization, you will be able to summon more intelligent beings to the planet. Just like you, they can live by inhabiting bodies, and once they gather enough strength, they can summon even more humans here.</p>
                        <p class="card-text">This calculator will allow you to keep track of the progress of colonization, recording the intricate interweavings of relationships between people who live through multiple lives.</p>
                    </div>
                </div>
            </div>
        </div>

    </x-layout.container>
</x-layout.main>
