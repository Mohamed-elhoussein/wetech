<?php

namespace App\Http\Controllers;

use App\Models\Welcome;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcomeusers()
    {
        $welcomes = Welcome::where('target', 'CLIENT_APP')->paginate(20);
        return view('welcome.index', compact('welcomes'));
    }
    public function welcomeProviders()
    {
        $welcomes = Welcome::where('target', 'PROVIDER_APP')->paginate(20);
        return view('welcome.index', compact('welcomes'));
    }
    public function create(Request  $request)
    {
        $this->validate($request, ['titel' => 'required', 'string']);

        $welcome =  Welcome::create([
            'titel'      => $request->titel,
            'titel_en'   => $request->titel_en,
            'body'       => $request->body,
            'body_en'    => $request->body_en,
            'target'     => $request->target,
            'image'  => upload_picture($request->file('image'), '/image/welcomes')
        ]);

        if ($welcome->target == 'CLIENT_APP')
            return   redirect()->route('welcome.users')->with('created', 'The welcome message was created ');
        else
            return redirect()->route('welcome.providers')->with('created', 'The welcome message was created ');
    }
    public function edit($id)
    {

        $welcome = Welcome::findOrFail($id);
        return view('welcome.edit', compact('welcome'));
    }
    public function update(Request $request, $id)
    {

        $welcome  = Welcome::findOrFail($id);
        $this->validate($request, ['titel' => 'required', 'string']);
        $fields   = $request->all();

        $welcome->titel       =   $request->titel;
        $welcome->titel_en    =   $request->titel_en;
        $welcome->body        =   $request->body;
        $welcome->body_en     =   $request->body_en;
        $welcome->target      = $request->target;

        isset($fields['image'])  ?  $welcome->image    =   upload_picture($request->file('image'), '/image/welcomes')   : false;

        $welcome->save();
        if ($welcome->target == 'CLIENT_APP')
            return   redirect()->route('welcome.users')->with('created', 'The welcome message was created ');
        else
            return redirect()->route('welcome.providers')->with('created', 'The welcome message was created ');
    }
    public function delete($id)
    {
        Welcome::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The welcome message was deleted ');
    }
}
