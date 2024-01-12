<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issues::query()->paginate();

        return view('issues.index', compact('issues'));
    }

    public function create()
    {
        return view('issues.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Issues::create($data);
        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.issues.index');
    }

    public function edit(Issues $issue)
    {
        return view('issues.edit', compact('issue'));
    }

    public function update(Issues $issue, Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $issue->update($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.issues.index');
    }

    public function destroy(Issues $issue) {
        $issue->delete();

        return redirect()->route('main.issues.index');
    }

    public function create_ajax()
    {
        return view('issues.modal');
    }
}
