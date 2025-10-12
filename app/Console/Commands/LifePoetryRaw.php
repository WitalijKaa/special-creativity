<?php

namespace App\Console\Commands;

use App\Http\Controllers\Person\Poetry\LifePoetryTranslateAction;
use App\Http\Controllers\Person\Poetry\LifePoetryTranslateAgainAction;
use App\Http\Controllers\Person\Poetry\LifePoetryVersionsAction;
use App\Models\AiRequest\ImproveWithLlm;
use App\Models\AiRequest\TranslateWithLlm;
use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class LifePoetryRaw extends Command
{
    protected $signature = 'poetry:raw {login} {life}';

    protected $description = 'Create ALPHA BETA EMOTION scripts';

    public function handle()
    {
        define('DB', $this->argument('login'));

        $life = $this->argument('life');
        $life = Life::whereId($life)->firstOrFail();

        new LifePoetryTranslateAction()($life->id);
        new LifePoetryVersionsAction()($life->id);
        new LifePoetryTranslateAgainAction()($life->id);

        /*
        $plan = array_values(config('basic.final_flow'));
        $planRaw = array_map(fn (string $stage) => explode('_', $stage), $plan);
        $fistStage = explode('.', $planRaw[0][0]);
        $improveStages = array_map(fn (string $stage) =>explode('.', explode('_', $stage)[1]), $plan);
        $lastStage = explode('.', $planRaw[0][2]);

        $firstStageLang = $fistStage[count($fistStage) - 1];

        $this->translate($fistStage, V_TRANSLATION, $life->poetry, $life->id);

        foreach ($improveStages as $stageImprove) {
            $config = LlmConfig::configByExplode($stageImprove);
            $improve = new ImproveWithLlm();
            $improve->useConfig($config);
            $response = $improve->improveChapter($life->poetrySpecific($firstStageLang, V_TRANSLATION));

            $specific = $this->specificKeyOfConfig(implode('.', $stageImprove));
            Poetry::whereLifeId($life->id)->whereLlm($specific)->whereLang($firstStageLang)->delete();
            foreach ($response as $model) {
                $model->llm = $specific;
                $model->save();
            }
        }

        foreach ($improveStages as $stageImprove) {
            $specific = $this->specificKeyOfConfig(implode('.', $stageImprove));
            $this->translate(
                $lastStage,
                $specific,
                $life->poetrySpecific($firstStageLang, $specific),
                $life->id
            );
        */
    }

    private function translate(array $stage, string $saveLlmName, Collection $poetry, int $lifeID)
    {
        $toLang = $stage[count($stage) - 1];

        $config = LlmConfig::configByExplode($stage);
        $translate = new TranslateWithLlm();
        $translate->useConfig($config);
        $response = $translate->translateChapter($poetry, $toLang);
        Poetry::whereLifeId($lifeID)->whereLlm($saveLlmName)->whereLang($toLang)->delete();
        foreach ($response as $model) {
            $model->llm = $saveLlmName;
            $model->save();
        }
    }

    private function specificKeyOfConfig(string $llm): string
    {
        foreach (config('basic.final_flow') as $specific => $flow) {
            if (str_contains($flow, $llm)) {
                return $specific;
            }
        }
        throw new \Exception('Unexpected boom!');
    }
}
