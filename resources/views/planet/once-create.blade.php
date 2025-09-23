<?php

$factory = new \App\Dto\Form\FormInputFactory();

$planetForm = [
    $factory->input('name', 'Planet Name', $factory->withValue('Genesis')),
    $factory->number('days', 'how many days in a Year?', $factory->withValue(420)),
    $factory->number('hours', 'how many hours in a Day?', $factory->withValue(36)),
    $factory->input('person', 'the Name of the First Person', $factory->withValue('Witalij')),
    $factory->input('nick', 'nick name', $factory->withValue('Dikoros')),
    $factory->number('force', 'what Force will he have to create other Persons at Start?', $factory->withValue(100)),
    $factory->number('force_create', 'when creates a new Person in the Allod-Creation how many Force reduces?', $factory->withValue(95)),
    $factory->number('force_man_up', 'how many Force during MAN life accumulates?', $factory->withValue(100)),
    $factory->number('force_woman_up', 'how many Force during regular-WOMAN life accumulates?', $factory->withValue(15)),
    $factory->number('force_woman_special_up', 'how many Force during special-WOMAN life accumulates?', $factory->withValue(95)),
    $factory->number('force_woman_man_allowed', 'how many MAN lives in a row should live until special-WOMAN life allowed?', $factory->withValue(4)),
    $factory->number('force_man_first_up', 'how many Force during first MAN lives of a created Person accumulates until 100?', $factory->withValue(25)),
    $factory->number('force_woman_first_up', 'how many Force during first WOMAN lives of a created Person accumulates until 100?', $factory->withValue(5)),
    $factory->number('force_man_min', 'min MAN life length to get Force', $factory->withValue(60)),
    $factory->number('force_woman_min', 'min WOMAN life length to get Force', $factory->withValue(60)),
];

?><x-layout.main>
    <x-layout.header-main>Create your science fiction world</x-layout.header-main>

    <x-form.basic :route="route('web.planet.save')"
                  btn="Create the Planet"
                  :fields="$planetForm"></x-form.basic>

    <x-layout.divider />

    <x-layout.container>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ruski</h4>
                        <p class="card-text">чтоб призвать новую душу нужна Force. она накапливается в материальном мире (на планете) как результат прожитой жизни</p>
                        <p class="card-text">призвать душу можно только в Ином Мире, на Аллодах, для этого Force расходуется. у человека не может быть Force более ста или менее нуля</p>
                        <p class="card-text">только что призванная душа накапливает Force медленее, женские жизни не позволяют накопить Force больше чем мужские (если вы тоже так желаете)</p>
                        <p class="card-text">прогресс колонизации планеты зависит от Force которая копится в человеческих душах на протяжении их жизненного пути</p>
                        <p class="card-text">(колличество дней и часов влияет только на расчёты потраченных за жизнь усилий на те или иные виды работ)</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Engleski</h4>
                        <p class="card-text">To summon a new soul, you need Force. It accumulates in the material world (on the planet) as the result of a lived life.</p>
                        <p class="card-text">A soul can only be summoned in the Other World, on the Allods, and for this, Force is consumed. A person cannot have more than one hundred Force, nor less than zero.</p>
                        <p class="card-text">A newly summoned soul accumulates Force more slowly, and female lives do not allow Force to be gathered in greater amounts than male lives (if you wish it so as well).</p>
                        <p class="card-text">The progress of the planet’s colonization depends on the Force accumulated in human souls throughout the course of their lives.</p>
                        <p class="card-text">(The number of days and hours only affects the calculations of the effort spent during life on various kinds of work.)</p>
                    </div>
                </div>
            </div>
        </div>
    </x-layout.container>

</x-layout.main>
