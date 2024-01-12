<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingApiController extends Controller
{

    public function details($rating_id)
    {
        $rating =  Rating::where('id', $rating_id)->with('order')->first();

        $data   =  $rating;

        return response()->data($data);
    }
    public function create(Request  $request)
    {
        $this->validate($request, rules('rating.create'));

        $rating = Rating::create([
            'order_id'          => $request->order_id,
            'rated_by'          => $request->rated_by,
            'stars'             => $request->rate,
            'experience'        => $request->exp,
            'performance'       => $request->pref,
            'respect_the_time'  => $request->time,
            'comment'           => $request->content,

        ]);


        $data    =  $rating;
        $message =  'rating was created successfully';

        return response()->data($data, $message);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, rules('rating.update'));
        $fields   = $request->all();

        $rating   = Rating::where('id', $id)->first();

        isset($fields['rate'])           ?   $rating->rate = $fields['rate']         :   false;
        isset($fields['content'])        ?   $rating->content = $fields['content']   :   false;

        $rating->save();

        $data = $rating;
        $message =  'rating was updated successfully';

        return response()->data($data, $message);
    }

    public function delete($id)
    {
        Rating::findOrFail($id)->delete();

        $message = 'rating was deleted successfully';

        return response()->message($message);
    }
    public function rating()
    {
    }
}
