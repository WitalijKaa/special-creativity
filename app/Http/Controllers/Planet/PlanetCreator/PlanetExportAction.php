<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Inteface\JsonArchivableInterface;
use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\Work\Work;
use App\Models\World\Life;
use App\Models\World\Planet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlanetExportAction
{
    private const CHUNK = 500;

    public function __invoke(Request $request)
    {
        if ($request->isMethod('post') && $this->jsonArchive(self::exportQueries())) {
            return redirect()->route('web.planet.export')->with('status', "Export SUCCESS!");
        }

        return view('planet.export', ['json' => $this->jsonSummary(self::exportQueries())]);
    }

    public static function exportQueries(): array
    {
        return [
            'planet' => Planet::orderBy('id'),
            'work' => Work::orderBy('begin'),
            'eventTypes' => EventType::where('id', '>', EventType::HOLY_LIFE)->orderBy('id'),
            'persons' => Person::orderBy('id'),
            'lives' => Life::orderBy('begin')->orderBy('person_id'),
            'events' => PersonEvent::with(['connections.person', 'person', 'life']),
            'poetry' => Poetry::orderBy('life_id')->orderBy('lang')->orderBy('ai')->orderBy('ix_text'),
            'poetryWords' => PoetryWord::orderBy('word'),
        ];
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
        $disk = Storage::disk('public');

        if ($disk->directoryExists($dirName)) {
            return false;
        }

        $disk->makeDirectory($dirName);

        foreach ($queries as $K => $query) {
            if (!$query->count()) {
                continue;
            }

            $fileName = $dirName . DIRECTORY_SEPARATOR . 'sc_export_' . $K . '.json';
            $stream = fopen($disk->path($fileName), 'w');
            fwrite($stream, "[");
            $this->streamWriteArchive($query, $stream);
            fwrite($stream, "\n]");
            fclose($stream);
        }

        return true;
    }

    private function streamWriteArchive(Builder $query, $stream): void
    {
        $first = true;
        $query->chunk(self::CHUNK, function ($clt) use ($stream, &$first) {
            foreach ($clt as $model) {
                /** @var JsonArchivableInterface $model */

                $json = ($first ? "\n" : ",\n") .
                    json_encode($model->archive(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                fwrite($stream, $json);
                $first = false;
            }
        });
    }
}
