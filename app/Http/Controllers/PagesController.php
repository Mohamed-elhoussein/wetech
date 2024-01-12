<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {

        $pages = Pages::paginate(20);

        return view('pages.index', compact('pages'));
    }
    public function create(Request  $request)
    {

        $this->validate($request, ['title' => 'required']);
        Pages::create([
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $this->upload_picture($request->file('thumbnail')),
            'active' => $request->active ? '1' : '0'
        ]);
        return redirect()->route('pages.index')->with('created', 'The page content was created ');
    }
    public function edit($id)
    {

        $pages = Pages::where('id', $id)->first();
        return view('pages.edit', compact('pages'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);
        if ($request->hasFile('thumbnail')) {
            Pages::where('id', $id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'thumbnail' => $this->upload_picture($request->file('thumbnail')),
                'active' => $request->active ? '1' : '0'
            ]);
        } else {
            Pages::where('id', $id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'active' => $request->active ? '1' : '0'
            ]);
        }

        return redirect()->route('pages.index')->with('updated', 'The page content was updated ');
    }
    public function delete($id)
    {
        Pages::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The page content was deleted ');
    }
    public function block($id)
    {
        $Pages  =   Pages::where('id', $id)->first();

        $Pages->active   = $Pages->active   ?   0   :   1;

        $Pages->save();

        return   redirect()->back();
    }

    public static function upload_picture($picture)
    {

        if ($picture) {
            $file_name = $picture->getClientOriginalName();

            $picture->move(public_path() . "/images/pages", $file_name);
            $local_url = $file_name;

            $picture_url = '/images/pages/' . $local_url;
            return $picture_url;
        }
        return "";
    }
}
