<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\AiRequest\TranslateWithAi;
use App\Models\Poetry\LanguageHelper;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterTranslateRequest;

class ChapterTranslateAction
{
    public function __invoke(int $life_id, ChapterTranslateRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->poetry_specific(LanguageHelper::oppositeLang($request->to_lang), null);

        if ($poetry->count() && LL_ENG == $request->to_lang) {
            $translate = new TranslateWithAi();
            $translate->ai = $request->llm;
            $response = $translate->translatePoetryMass($poetry);

            Poetry::whereLifeId($life->id)->whereAi($request->llm)->delete();
            foreach ($response as $model) {
                $model->save();
            }
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
