<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Work\Work;
use App\Models\World\Life;
use App\Models\World\Planet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlanetExportAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();
        $work = Work::orderBy('begin')->get();
        $eventTypes = EventType::where('id', '>', EventType::HOLY_LIFE)->orderBy('id')->get();
        $persons = Person::orderBy('id')->get();
        $lives = Life::all();
        $events = PersonEvent::with(['connections.person', 'person', 'life'])->get();

        $json = [
            'planet' => $planet->archive(),
            'work' => $work->map(fn (\App\Models\Work\Work $model) => $model->archive()),
            'eventTypes' => $eventTypes->map(fn (\App\Models\Person\EventType $model) => $model->archive()),
            'persons' => $persons->map(fn (\App\Models\Person\Person $model) => $model->archive()),
            'lives' => $lives->map(fn (\App\Models\World\Life $model) => $model->archive()),
            'events' => $events->map(fn (\App\Models\Person\PersonEvent $model) => $model->archive()),
        ];

        if ($request->isMethod('post')) {
            $filename = 'sc_export_' . now()->format('Md_H-i') . '.json';
            Storage::disk('public')->put($filename, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return redirect()->route('web.planet.export')->with('status', "Export SUCCESS! to $filename");
        }

        return view('planet.export', ['json' => $json]);
    }
}
