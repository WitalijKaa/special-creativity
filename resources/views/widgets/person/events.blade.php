<?php

/** @var \App\Models\Person\Person $person */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \App\Models\Person\PersonEvent $event */
/** @var \App\Models\Person\PersonEventConnect $connect */
/** @var \App\Models\World\LifeWork $lifeWork */

$vEvent = new \App\Models\View\EventView();
$lifeWork ??= null;

// DEV {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} .
//dd($events, $person->lives->last());

?><ul class="list-group">
    @foreach($events as $event)
        @php($viewLife = $event->lifeOfPerson($person->id))

        <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$vEvent->backColor($event)}}">

            <div class="d-flex w-50 justify-content-between">
                <span>
                    {{$event->type->name}}
                    {!!$vEvent->lifeGenre($viewLife)!!}
                    {!!$vEvent->loveConnectionGenre($event, $viewLife)!!}
                    <small>{{$event->comment}}</small>
                    {!!$vEvent->labelStartAge($event, $viewLife)!!}
                    {!!$vEvent->labelWork($event)!!}
                </span>
                <em>{!!$vEvent->labelRange($event)!!}</em>
            </div>

            <div class="d-flex w-50 justify-content-between">
                <span>{!!$vEvent->labelWorkDays($event, $lifeWork)!!}</span>
                <span>
                    @if($event->person_id != $person->id)
                        <span class="badge text-bg-success rounded-pill">{{$event->person->name}}</span>
                    @endif
                    @foreach($event->connections as $connect)
                        @if($connect->person_id != $person->id)
                            <span class="badge text-bg-success rounded-pill">{{$connect->person->name}}</span>
                        @endif
                    @endforeach
                </span>
            </div>
        </li>
    @endforeach
</ul>
