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

    public static function selectOptions(?string $exceptLang = null): array
    {
        $return = [
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
        ];
        return $exceptLang ? array_values(array_filter($return, fn ($item) => $exceptLang != $item['opt'])) : $return;
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
}
