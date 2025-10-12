<?php

namespace App\Console\Commands;

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

        $plan = array_values(config('basic.final_flow'));
        $planRaw = array_map(fn (string $stage) => explode('_', $stage), $plan);
        $fistStage = explode('.', $planRaw[0][0]);
        $improveStages = array_map(fn (string $stage) =>explode('.', explode('_', $stage)[1]), $plan);
        $lastStage = explode('.', $planRaw[0][2]);

        $firstStageLang = $fistStage[count($fistStage) - 1];
        $firstStageLlmName = implode('.', $fistStage);

        $this->translate($fistStage, $firstStageLlmName, $life->poetry, $life->id);
        dump('fistStage');

        foreach ($improveStages as $stageImprove) {
            $config = $this->llmConfig($stageImprove);
            $improve = new ImproveWithLlm();
            $improve->useConfig($config);
            $response = $improve->improveChapter($life->poetrySpecific($firstStageLang, $firstStageLlmName));

            $nextLlmName = $firstStageLlmName . '_' . implode('.', $stageImprove);
            Poetry::whereLifeId($life->id)->whereLlm($nextLlmName)->whereLang($firstStageLang)->delete();
            foreach ($response as $model) {
                $model->llm = $nextLlmName;
                $model->save();
            }
            dump('stageImprove ' . $nextLlmName);
        }

        foreach ($improveStages as $stageImprove) {

            $prevStagesName = $firstStageLlmName . '_' . implode('.', $stageImprove);
            $lastStageLlmName = implode('.', $lastStage);

            $this->translate(
                $lastStage,
                $prevStagesName . '_' . $lastStageLlmName,
                $life->poetrySpecific($firstStageLang, $prevStagesName),
                $life->id
            );
            dump('translate ' . $prevStagesName);
        }
    }

    private function llmConfig(array $stage): LlmConfig
    {
        $config = new LlmConfig($stage[0]);
        foreach ($stage as $param) {
            $config->applyPipeParam($param);
        }
        return $config;
    }

    private function translate(array $stage, string $saveLlmName, Collection $poetry, int $lifeID)
    {
        $toLang = $stage[count($stage) - 1];

        $config = $this->llmConfig($stage);
        $translate = new TranslateWithLlm();
        $translate->useConfig($config);
        $response = $translate->translateChapter($poetry, $toLang);
        Poetry::whereLifeId($lifeID)->whereLlm($saveLlmName)->whereLang($toLang)->delete();
        foreach ($response as $model) {
            $model->llm = $saveLlmName;
            $model->save();
        }
    }
}
