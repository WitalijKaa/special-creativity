<?php

$year ??= null;
/** @var \App\Models\Person\Person $person */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \App\Models\Person\PersonEvent $event */
/** @var \App\Models\Person\PersonEventConnect $connect */
/** @var \App\Models\Work\LifeWork $lifeWork */

$vEvent = new \App\Models\View\EventView();
$lifeWork ??= null;
$showWorks = $showWorks ?? empty($person);
$showGender = !empty($showGender) || !empty($person);

// DEV {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} .

?>
<ul class="list-group">
    @foreach($events as $event)
        @php($personOfEvent = !empty($person) ? $person : $event->life->person)
        @php($viewLife = $event->lifeOfPerson($personOfEvent->id))
        @php($backStyle = $vEvent->backColor($event))
        @php($backStyle = $showWorks && $year > 0 && ($year < $event->begin || $year > $event->end) ? CC_DARK : $backStyle)

        @if($event->work_id)
            <a href="{{ route('web.planet.event-edit-form', ['id' => $event->id]) }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$backStyle}}">
        @else
            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-{{$backStyle}}">
        @endif


            <div class="d-flex w-50 justify-content-between">
                <span>
                    {{-- {{$event instanceof \App\Models\Person\PersonEvent ? $event->id : ''}} --}}
                    {{$event->type->name}}
                    @if($showGender)
                        {!!$vEvent->lifeGender($viewLife)!!}
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
                    @if($showWorks)
                        {!!$vEvent->labelWorkLivesPercent($event)!!}
                    @endif
                </em>
            </div>

            <div class="d-flex w-50 justify-content-between">
                <span style="white-space: nowrap; padding-right: 5px;">
                    @if($showWorks)
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
