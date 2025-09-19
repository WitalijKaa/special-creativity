<?php

namespace App\Models\Poetry;

class LanguageHelper
{
    public static function oppositeLang(string $lang): string
    {
        if ($lang == 'rus') {
            return 'eng';
        }
        return 'rus';
    }

    public static function selectOptions(): array
    {
        return [
            [
                'opt' => 'rus',
                'lbl' => 'Russian',
            ],
            [
                'opt' => 'eng',
                'lbl' => 'English',
            ],
            [
                'opt' => 'ukr',
                'lbl' => 'Ukrainian',
            ],
            [
                'opt' => 'srb',
                'lbl' => 'Serbian',
            ],
            [
                'opt' => 'fra',
                'lbl' => 'French',
            ],
        ];
    }

    public static function label(string $lang): string
    {
        foreach (static::selectOptions() as $option) {
            if ($lang == $option['opt']) {
                return $option['lbl'];
            }
        }
        return $lang;
    }

    public static function selectTranslateFromOriginalOptions(): array
    {
        return [
            [
                'opt' => 'eng',
                'lbl' => 'English',
            ],
            [
                'opt' => 'fra',
                'lbl' => 'French',
            ],
        ];
    }

    public static function selectAiOptions(): array
    {
        $return = [];
        foreach (config('basic.llm_models') as $model) {
            $return[] = [
                'opt' => $model,
                'lbl' => ucfirst($model),
            ];
        }
        return $return;
    }
}
