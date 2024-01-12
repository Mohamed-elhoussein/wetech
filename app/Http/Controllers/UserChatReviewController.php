<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\User;
use App\Models\permission;
use App\Models\monitorPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\FCM;

class UserChatReviewController extends Controller
{
    public function create()

    {
        $countries  =   Countries::get(['id', 'name', 'code', 'country_code', 'status']);

        return   view('chat_review.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $number_phone   =   $request->number_phone;
        $country_id     =   $request->country_id;
        $this->validate(
            $request,
            [
                'number_phone' => [
                    Rule::unique('users')->where(function ($query) use ($number_phone, $country_id) {
                        return $query->where('number_phone', $number_phone)
                            ->where('country_id', $country_id)
                            ->where('role', 'chat_review');
                    })
                ]
            ],
            ['number_phone.unique' => "The observer of chat is already created !"]
        );


        $this->validate($request, rules('chat_review.create'));
        $fields       = $request->all();

        $permissions = collect(chatReviewsPermission())->mapWithKeys(function ($permission) use ($request) {
            return [
                $permission => in_array($permission, array_keys($request->chat_review)),
            ];
        })->toArray();

        User::create([
            'number_phone'   =>       $fields['number_phone'],
            'username'       =>       $fields['username'] ?? null,
            'country_id'     =>       $fields['country_id'],
            'first_name'     =>       $fields['first_name'],
            'avatar'         =>       isset($fields['avatar']) ? upload_picture($fields['avatar'], '/images/avatars') : '/images/avatars/default.png',
            'second_name'    =>       $fields['second_name'],
            'last_name'      =>       $fields['last_name'],
            'role'           =>       'chat_review',
            'email'          =>       $fields['email'],
            'verified'       =>       true,
            'permissions'    =>      array_keys($request->permission ?? []),
            'chat_reviews_permissions' => $permissions
        ]);
        return redirect()->route('user.chat_reviews')->with('created', 'the observer was created successefly');
    }

    public function edit($id)
    {
        $countries      =       Countries::get(['id', 'name', 'code', 'country_code', 'status']);
        $observer       =       User::with('country:id,name,code,country_code')->findOrFail($id);
        $status         =       monitorPermission::with('permission')->where('monitor_id' , 90 )->get();

       

        if (!is_array($observer->chat_reviews_permissions))
            $observer->chat_reviews_permissions = json_decode($observer->chat_reviews_permissions);

        return view('chat_review.edit', compact('observer', 'countries' , 'status'));
    }

    public function update(Request $request, $id)
    {
        $permissions = collect(chatReviewsPermission())->mapWithKeys(function ($permission) use ($request) {
            return [
                $permission => in_array($permission, array_keys($request->chat_review)),
            ];
        })->toArray();

        $this->validate($request, rules('chat_review.create'));
        $fields     =   $request->all();
        $observer   =   User::findOrFail($id);

        $observer->username       =       $fields['username'];
        $observer->email          =       $fields['email'];
        $observer->number_phone   =       $fields['number_phone'];
        $observer->country_id     =       $fields['country_id'];
        $observer->avatar         =       isset($fields['avatar'])      ?       upload_picture($request->file('avatar'), '/images/avatars') : $observer->avatar;
        $observer->first_name     =       $fields['first_name'];
        $observer->second_name    =       $fields['second_name'];
        $observer->last_name      =       $fields['last_name'];
        $observer->permissions    =      array_keys($request->permission ?? $observer->permission);
        $observer->chat_reviews_permissions = $permissions;
        $observer->save();

        $permissons               = $request->chat_review;
        $observer_permission      = monitorPermission::findOrFail($id);

        foreach($observer_permission as $per){


                
        }

        // if ($request->permission) {
        //     $device_token     =   $observer->device_token;
        //     if ($device_token) {
        //         $fcm                =    new FCM();
        //         $message_payload    =   ["new_permissions" => $observer->chat_reviews_permissions];
        //         $fcm->to($device_token)->message_payload($message_payload)->data(NULL, 'chat_reviews_permissions', NULL, 'chat_reviews_permissions')->send();
        //     }
        // }

        return redirect()->route('user.chat_reviews')->with('updated', 'the observer was updated successefly');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return  redirect()->back()->with('deleted', 'the observer was deleted successefly');
    }
}
