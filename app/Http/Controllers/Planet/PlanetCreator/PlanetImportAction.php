<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Migrations\Migrator;
use App\Models\World\ForceEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlanetImportAction
{
    public function __invoke(Request $request)
    {
        if ($request->isMethod('post') && $dir = $request->get('directory')) {
            try {
                $this->importFromDirectory($dir);
                ForceEvent::reWriteCreationsAndLives();
            } catch (\Throwable $ex) {
                return redirect()->route('web.planet.import')
                    ->with(APP_ERR, 'Error while import:' . $ex->getMessage());
            }

            return redirect()->route('web.planet.import')
                ->with(APP_MSG, 'Import done - ' . $request->get('directory'));
        }

        return view('planet.import');
    }

    public static function importFiles(): array
    {
        $export = PlanetExportAction::exportQueries();
        $import = [];
        foreach ($export as $K => $builder) {
            /** @var $builder \Illuminate\Database\Eloquent\Builder */

            $import[$builder->getModel()::class] = "sc_export_$K.json";
        }
        return $import;
    }

    private function importFromDirectory(string $dir): void
    {
        if (!Storage::disk('public')->directoryExists($dir)) {
            throw new \InvalidArgumentException('No such directory');
        }

        Migrator::drop();
        Migrator::migrate();

        foreach (self::importFiles() as $className => $fileName) {
            $path = $dir . DIRECTORY_SEPARATOR . $fileName;
            $json = $this->entitiesJson($path);
            /** @var $className \App\Models\World\Planet|\App\Models\Person\Person|\App\Models\World\Life|\App\Models\Person\PersonEvent|\App\Models\Poetry\Poetry */
            foreach ($json as $item) {
                $className::fromArchive($item);
            }
        }
    }

    private function entitiesJson(string $path): array
    {
        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            return json_decode($disk->get($path), true);
        }
        return [];
    }
}




