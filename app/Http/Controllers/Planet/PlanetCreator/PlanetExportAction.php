<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Inteface\JsonArchivableInterface;
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
        $queries = [
            'work' => Work::orderBy('begin'),
            'eventTypes' => EventType::where('id', '>', EventType::HOLY_LIFE)->orderBy('id'),
            'persons' => Person::orderBy('id'),
            'lives' => Life::all(),
            'events' => PersonEvent::with(['connections.person', 'person', 'life'])->get(),
        ];

        if ($request->isMethod('post') && $this->jsonArchive($queries)) {
            return redirect()->route('web.planet.export')->with('status', "Export SUCCESS! to $dirName");
        }

        return view('planet.export', ['json' => $this->jsonSummary($queries)]);
    }

    private function jsonSummary(array $queries): array
    {
        foreach ($queries as $K => $query) {
            $queries[$K] = $query->count();
        }
        return $queries;
    }

    private function jsonArchive(array $queries): bool
    {
        $dirName = 'sc_export_' . now()->format('Md_H-i');
        if (!Storage::disk('public')->directoryExists($dirName)) {
            Storage::disk('public')->makeDirectory($dirName);

            foreach ($queries as $K => $query) {
                $fileName = 'sc_export_' . $K . '.json';
                $archive = $query->get()->map(fn (JsonArchivableInterface $model) => $model->archive());
                Storage::disk('public')->put($fileName, json_encode($archive, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            $planet = Planet::first();
            $fileName = 'sc_export_planet.json';
            Storage::disk('public')->put($fileName, json_encode($planet->archive(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return true;
        }
        return false;
    }
}
