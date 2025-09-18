<?php

return [
    'lang' => env('BASIC_LANG', 'rus'),

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
