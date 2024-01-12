<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;

class SkillApiController {

    public function store(SkillRequest $request)
    {
        $skill = Skill::create($request->validated());
        return response()->data($skill);
    }

    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());
        return response()->data($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->message('تم حذف المهارة بنجاح');
    }

    public function getAll()
    {
        $skills = Skill::all();
        return response()->data($skills);
    }
}
