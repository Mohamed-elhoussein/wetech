<?php

namespace App\Http\Controllers;

use App\Http\Filters\SubscribeFilter;
use App\Models\Subscribe;
use App\Models\SubscribePackes;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{

    public function index(SubscribeFilter $filter)
    {
        $subscribers = Subscribe::filter($filter)->where('is_paid', 1)->with('user')->paginate(request()->get('limit', 15))->withQueryString();
        return view('subscribers', compact('subscribers'));
    }
    public function subscribes()
    {
        $subscribesPackes = SubscribePackes::paginate();
        return view('subscribes.index', compact('subscribesPackes'));
    }
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'name_en' => 'required']);
        $pack = SubscribePackes::create($request->except(['_token']));
        return redirect()->route('subscribe.pack.index')->with(['created' => 'the subscribe pack was created']);
    }
    public function edit($id)
    {
        $pack = SubscribePackes::where('id', $id)->firstOrFail();
        return view('subscribes.edit', compact('pack'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required', 'name_en' => 'required']);
        $pack = SubscribePackes::where('id', $id)->update($request->except(['_token']));
        return redirect()->route('subscribe.pack.index')->with(['updated' => 'the subscribe pack was updated']);
    }
    public function delete(Request $request, $id)
    {

        $pack = SubscribePackes::where('id', $id)->delete();
        return redirect()->route('subscribe.pack.index')->with(['deleted' => 'the subscribe pack was deleted']);
    }
}
