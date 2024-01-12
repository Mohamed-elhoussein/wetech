<?php

namespace App\Http\Controllers;

use App\Http\Filters\RatingFilter;
use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function rates(RatingFilter $filter)
    {
        $rates = Rating::filter($filter)->with('user:id,username')->latest()->paginate(request()->get('limit', 15))->withQueryString();

        return view('ratings.index', compact('rates'));
    }

    public  function edit($id)
    {
        $rate   = Rating::where('id', $id)->firstOrFail();
        return view('ratings.edit', compact('rate'));
    }

    public  function update(Request $request, $id)
    {
        $this->validate($request, ['stars' => 'required', 'comment' => 'required']);
        $rate   = Rating::where('id', $id)->update([
            'stars' => $request->stars,
            'comment' => $request->comment
        ]);
        return  redirect()->route('rate.index')->with(['updated' => 'rate was updated']);
    }


    public function delete($id)
    {

        $rate   = Rating::where('id', $id)->delete();

        return  redirect()->back()->with(['deleted' => 'rate was deleted']);
    }
}
