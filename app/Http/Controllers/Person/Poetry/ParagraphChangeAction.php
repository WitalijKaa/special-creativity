<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Requests\Poetry\PoetryParagraphChangeRequest;

class ParagraphChangeAction
{
    public function __invoke(int $id, PoetryParagraphChangeRequest $request)
    {
        $model = Poetry::query()->findOrFail($id);
        $model->text = trim($request->text);
        $model->save();

        return redirect(route('web.person.poetry-life-edit', ['life_id' => $model->life_id, 'lang' => $model->lang, 'llm' => $model->llm]) .
            '#p_' . $model->ix_text);
    }
}
