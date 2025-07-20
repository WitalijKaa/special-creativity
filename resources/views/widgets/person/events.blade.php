<?php

/** @var int $personID */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \App\Models\Person\PersonEvent $event */
/** @var \App\Models\Person\PersonEventConnect $connect */

$vEvent = new \App\Models\View\EventView();

// DEV {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} .

?><ul class="list-group">
    @foreach($events as $event)
        @php($myEvent = $event->person_id == $personID)

        <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$vEvent->backColor($event)}}">
            <span>{{$event->type->name}} <em>{{$vEvent->labelRange($event)}}</em> {!!$vEvent->labelGenreMy($event, $myEvent)!!} <small>{{$event->comment}}</small></span>
            <span>
                @if(!$myEvent)
                    <span class="badge text-bg-success rounded-pill">{{$event->person->name}}</span>
                @endif
                @foreach($event->connections as $connect)
                    @if($connect->person_id != $personID)
                        <span class="badge text-bg-success rounded-pill">{{$connect->person->name}}</span>
                    @endif
                @endforeach
            </span>
        </li>
    @endforeach
</ul>
