<?php

/** @var \App\Models\Person\Person $person */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \App\Models\Person\PersonEvent $event */
/** @var \App\Models\Person\PersonEventConnect $connect */

$vEvent = new \App\Models\View\EventView();

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
                </span>
                <em>[{{$vEvent->labelRange($event)}}]</em>
            </div>

            <span>
                <span class="badge text-bg-success rounded-pill">{{$person->name}}</span>
                @foreach($event->connections as $connect)
                    @if($connect->person_id != $person->id)
                        <span class="badge text-bg-success rounded-pill">{{$connect->person->name}}</span>
                    @endif
                @endforeach
            </span>
        </li>
    @endforeach
</ul>
