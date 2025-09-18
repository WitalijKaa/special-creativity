<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ParagraphAddRequest;

class ParagraphAddAction
{
    public function __invoke(int $life_id, ParagraphAddRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        collect(explode("\r\n\r\n", trim($request->paragraph)))
            ->each(function (string $paragraph) use ($request, $life) {
                $model = new Poetry();
                $model->text = trim($paragraph);
                $model->life_id = $life->id;
                $model->person_id = $life->person->id;
                $model->lang = $request->lang;
                $model->begin = $request->begin;
                $model->end = $request->end ?? $request->begin;

                $lastIX = Poetry::whereLifeId($life->id)
                    ->whereLang($request->lang)
                    ->whereNull('ai')
                    ->orderByDesc('ix_text')
                    ->first()
                    ?->ix_text ?? 0;
                $model->ix_text = $lastIX + 1;

                $model->save();
            });

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
