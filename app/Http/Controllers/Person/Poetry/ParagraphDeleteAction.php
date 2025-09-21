<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;

class ParagraphDeleteAction
{
    public function __invoke(int $id)
    {
        $model = Poetry::whereId($id)->firstOrFail();
        Poetry::whereId($id)->delete();

        $others = Poetry::where('lang', '!=', $model->lang)
            ->where('ai', '!=', $model->ai)
            ->whereLifeId($model->life_id)
            ->whereIxText($model->ix_text)
            ->get();

        $this->updateIxAfterDelete($model);
        foreach ($others as $other) {
            $other->delete();
            $this->updateIxAfterDelete($other);
        }

        return redirect()->route('web.person.poetry-life-edit', ['life_id' => $model->life_id, 'lang' => $model->lang, 'llm' => $model->ai ?? 'null']);
    }

    private function updateIxAfterDelete(Poetry $model): void
    {
        Poetry::query()->whereLang($model->lang)->whereAi($model->ai)->whereLifeId($model->life_id)
            ->orderBy('ix_text')
            ->get()
            ->each(function (Poetry $poetry, int $index): void {
                $poetry->ix_text = $index + 1;
                $poetry->save();
            });
    }
}
