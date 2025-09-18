<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Work\Work;
use App\Models\World\Life;
use App\Models\World\Planet;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PlanetExportAction
{
    public function __invoke(Request $request)
    {
        if ($request->isMethod('post')) {
            [$jsonSummary, $folderName] = $this->exportToFiles();

            session()->flash('status', "Export SUCCESS! to directory $folderName");

            return view('planet.export', ['json' => $jsonSummary]);
        }

        $jsonSummary = $this->buildSummary();

        return view('planet.export', ['json' => $jsonSummary]);
    }

    /**
     * @return array{array<string, Collection>, string}
     */
    private function exportToFiles(): array
    {
        $folderName = now()->format('Md_H-i');
        $disk = Storage::disk('public');

        if (! $disk->exists($folderName)) {
            $disk->makeDirectory($folderName);
        }

        $jsonSummary = [];

        $planet = Planet::first();
        if ($planet !== null) {
            $this->writeJson($disk, $folderName, 'planet', $planet->archive());
        }

        unset($planet);

        $jsonSummary['work'] = $this->exportCollection(
            $disk,
            $folderName,
            'work',
            Work::orderBy('begin')->get(),
            fn (\App\Models\Work\Work $model) => $model->archive(),
        );

        $jsonSummary['eventTypes'] = $this->exportCollection(
            $disk,
            $folderName,
            'eventTypes',
            EventType::where('id', '>', EventType::HOLY_LIFE)->orderBy('id')->get(),
            fn (\App\Models\Person\EventType $model) => $model->archive(),
        );

        $jsonSummary['persons'] = $this->exportCollection(
            $disk,
            $folderName,
            'persons',
            Person::orderBy('id')->get(),
            fn (\App\Models\Person\Person $model) => $model->archive(),
        );

        $jsonSummary['lives'] = $this->exportCollection(
            $disk,
            $folderName,
            'lives',
            Life::all(),
            fn (\App\Models\World\Life $model) => $model->archive(),
        );

        $jsonSummary['events'] = $this->exportCollection(
            $disk,
            $folderName,
            'events',
            PersonEvent::with(['connections.person', 'person', 'life'])->get(),
            fn (\App\Models\Person\PersonEvent $model) => $model->archive(),
        );

        return [$jsonSummary, $folderName];
    }

    /**
     * @param callable(TModel): array $transformer
     *
     * @template TModel of object
     *
     * @return Collection
     */
    private function exportCollection(FilesystemAdapter $disk, string $folderName, string $key, Collection $collection, callable $transformer): Collection
    {
        $archives = $collection->map($transformer)->values()->all();

        $this->writeJson($disk, $folderName, $key, $archives);

        $count = $collection->count();

        unset($collection, $archives);

        return $this->makeCountCollection($count);
    }

    private function writeJson(FilesystemAdapter $disk, string $folderName, string $key, $data): void
    {
        $filename = $folderName . '/sc_export_' . $key . '.json';

        $disk->put($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, Collection>
     */
    private function buildSummary(): array
    {
        return [
            'work' => $this->makeCountCollection(Work::count()),
            'eventTypes' => $this->makeCountCollection(
                EventType::where('id', '>', EventType::HOLY_LIFE)->count()
            ),
            'persons' => $this->makeCountCollection(Person::count()),
            'lives' => $this->makeCountCollection(Life::count()),
            'events' => $this->makeCountCollection(PersonEvent::count()),
        ];
    }

    private function makeCountCollection(int $count): Collection
    {
        return new class($count) extends Collection {
            private int $summaryCount;

            public function __construct(int $count)
            {
                $this->summaryCount = $count;

                parent::__construct([]);
            }

            public function count($mode = COUNT_NORMAL)
            {
                return $this->summaryCount;
            }
        };
    }
}
