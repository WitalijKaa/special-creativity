<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Requests\Poetry\PoetryParagraphChangeRequest;
use Illuminate\Http\RedirectResponse;

class ParagraphChangeAction
{
    public function __invoke(int $id, PoetryParagraphChangeRequest $request): RedirectResponse
    {
        $model = Poetry::query()->findOrFail($id);
        $model->text = trim($request->text);
        $model->save();

        return redirect()->route('web.person.poetry-life', ['life_id' => $model->life_id]);
    }
}
