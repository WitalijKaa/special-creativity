<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use Illuminate\Support\Facades\DB;

class ParagraphMoveDownAction
{
    public function __invoke(int $id)
    {
        $model = Poetry::findOrFail($id);

        $others = Poetry::where('lang', '!=', $model->lang)
            ->where('llm', '!=', $model->llm)
            ->whereLifeId($model->life_id)
            ->whereIxText($model->ix_text)
            ->get();

        if ($this->moveDown($model)) {
            foreach ($others as $other) {
                $this->moveDown($other);
            }
        }

        return redirect()->route('web.person.poetry-life-edit', [
            'life_id' => $model->life_id,
            'lang' => $model->lang,
            'llm' => $model->llm ?? 'a-null',
        ]);
    }

    private function moveDown(Poetry $model): bool
    {
        if (!$next = Poetry::whereLifeId($model->life_id)
            ->whereLang($model->lang)
            ->whereLlm($model->llm)
            ->whereIxText($model->ix_text + 1)
            ->first())
        {
            return false;
        }

        $model->ix_text++;
        $next->ix_text--;
        return $model->save() && $next->save();
    }
}
