<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;

class LifePoetryMasterAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $master = MASTER;
        $ix = 0;
        while (Poetry::whereLifeId($life_id)->whereLang($lang)->whereLlm($master)->exists()) {
            $master = MASTER . '_' . ++$ix;
        }

        $llm = 'null' == $llm ? null : $llm;
        Poetry::whereLifeId($life_id)
            ->whereLang($lang)
            ->whereLlm($llm)
            ->get()
            ->each(function (Poetry $poetry) use ($master) {
                $master = $poetry->llmModification($poetry->text, $poetry->lang ?? LL_RUS, $master);
                $master->save();
            });

        return redirect()->route('web.person.poetry-life-edit', ['life_id' => $life_id, 'lang' => $lang, 'llm' => $master]);
    }
}
