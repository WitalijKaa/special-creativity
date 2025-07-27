<?php

/** @var \App\Models\Person\Person $person */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \App\Models\Person\PersonEvent $event */
/** @var \App\Models\Person\PersonEventConnect $connect */
/** @var \App\Models\Work\LifeWork $lifeWork */

$vEvent = new \App\Models\View\EventView();
$lifeWork ??= null;

// DEV {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} .

?>
<ul class="list-group">
    @foreach($events as $event)
        @php($personOfEvent = !empty($person) ? $person : $event->life->person)
        @php($viewLife = $event->lifeOfPerson($personOfEvent->id))

        @if($event->work_id)
            <a href="{{ route('web.planet.event-edit-form', ['id' => $event->id]) }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$vEvent->backColor($event)}}">
        @else
            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$vEvent->backColor($event)}}">
        @endif


            <div class="d-flex w-50 justify-content-between">
                <span>
                    {{-- {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} --}}
                    {{$event->type->name}}
                    @if(!empty($person))
                        {!!$vEvent->lifeGenre($viewLife)!!}
                        {!!$vEvent->loveConnectionGenre($event, $viewLife)!!}
                    @endif
                    <small>{{$event->comment}}</small>
                    @if(!empty($person))
                        {!!$vEvent->labelAge($event, $viewLife)!!}
                    @endif
                    {!!$vEvent->labelWork($event)!!}
                </span>
                <em>
                    {!!$vEvent->labelRange($event)!!}
                    @if(empty($person))
                        {!!$vEvent->labelWorkLivesPercent($event)!!}
                    @endif
                </em>
            </div>

            <div class="d-flex w-50 justify-content-between">
                <span>
                    @if(empty($person))
                        {!!$vEvent->labelWorkLivesAmount($event)!!}
                    @else
                        {!!$vEvent->labelWorkAmount($event, $lifeWork)!!}
                    @endif
                </span>
                <span @if(empty($person)) class="w-75" @endif>
                    @if($event->person_id != $personOfEvent->id || empty($person))
                        <span class="badge text-bg-success rounded-pill">
                            {{$event->person->name}}
                            @if(empty($person))
                                {!!$vEvent->labelAgeShort($event, $event->life)!!}
                            @endif
                        </span>
                    @endif
                    @foreach($event->connections as $connect)
                        @if($connect->person_id != $personOfEvent->id)
                            <span class="badge text-bg-success rounded-pill">
                                {{$connect->person->name}}
                                @if(empty($person))
                                    {!!$vEvent->labelAgeShort($event, $connect->life)!!}
                                @endif
                            </span>
                        @endif
                    @endforeach
                </span>
            </div>

        @if(!$event->work_id)
            </li>
        @else
            </a>
        @endif
    @endforeach
</ul>
