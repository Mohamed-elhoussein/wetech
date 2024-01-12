<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\Issues;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index()
    {
        return response()->data(Issues::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Issues::create($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function update(Issues $issue) {
        $data = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $issue->update($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(Issues $issue) {
        $issue->delete();

        return response()->data([
            'success' => true
        ]);
    }
}
