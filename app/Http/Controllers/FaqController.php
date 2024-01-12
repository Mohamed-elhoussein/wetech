<?php

namespace App\Http\Controllers;

use App\Http\Filters\FaqFilter;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{

    public function index(FaqFilter $filter)
    {
        $faqs = Faq::filter($filter)->paginate(request()->get('limit', 15))->withQueryString();
        return view('faq.index', compact('faqs'));
    }
    public function create()
    {
        return view('faq.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, rules('faq.create'), rules_messages('faq.create'));

        Faq::create([
            'title' => $request->title,

            'content' => $request->content,

        ]);
        return redirect()->route('faq.index')->with('created', 'The questions was created ');
    }
    public function edit($id)
    {
        $faq = Faq::find($id);

        return view('faq.edit', compact('faq'));
    }
    public function update(Request $request, $id)
    {
        Faq::where('id', $id)->update([
            'title' => $request->title,

            'content' => $request->content,

        ]);
        return redirect()->route('faq.index')->with('updated', 'The questions was updated ');
    }
    public function delete($id)
    {
        Faq::find($id)->delete();
        return redirect()->route('faq.index')->with('deleted', 'The questions was deleted ');
    }
}
