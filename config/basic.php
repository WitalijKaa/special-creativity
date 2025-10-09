<?php

return [
    'lang' => env('BASIC_LANG', 'rus'),

    'llm_host' => env('LLM_HOST'),
    //'llm_models' => ['meta','france','russia','slavic','britain','europa','china','google','microsoft'],
    'llm_models' => ['meta','russia','france','gpt_best','gpt_mini','gpt_nano','china','google','microsoft'],
    'llm_models_to_slavic' => ['russia','france','gpt_mini','gpt_nano'],
    'llm_models_final' => ['gpt_mini','gpt_nano','gpt_best','russia'],

    'final_flow' => [
        'alpha' => 'meta.strict.nice.eng_meta.creatively.ok.more.improve_russia.strict.ok.rus',
        'beta' => 'meta.strict.nice.eng_russia.creatively.ok.improve_russia.strict.ok.rus',
        'emotion' => 'meta.strict.nice.eng_france.creatively.ok.insane.improve_russia.strict.ok.rus',
    ],

    'standardSupplyWorkerYears' => (int) env('WORK_STANDARD_SUPPLY_YEARS'),
    'workArmy' => [
        'prefix' => env('WORK_ARMY_PREFIX'),
        'minAge' => (int) env('WORK_ARMY_MIN_AGE'),
        'maxAge' => (int) env('WORK_ARMY_MAX_AGE'),
        'slaveType' => env('WORK_ARMY_PERIOD_TRIBE'),
        'childWorkStartAge' => (int) env('WORK_ARMY_CHILD_START_AGE'),
        'childWorkEndAge' => (int) env('WORK_ARMY_CHILD_END_AGE'),
        'adultWorkStartAge' => (int) env('WORK_ARMY_ADULT_START_AGE'),
    ],
    'eventTypes' => [
        'manSlave' => env('EVENT_TYPE_MAN_SLAVE'),
    ],
];
