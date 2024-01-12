<?php

namespace App\Http\Controllers\api;

use App\Enum\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\User;
use App\Models\Chat;
use App\Helpers\FCM;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function details($report_id)
    {
        $report =  Reports::where('id',$report_id)->with('user')->first();

        $data   =  $report ;

        return response()->data($data);
    }
    public function create(Request  $request)
    {
        $this->validate($request,rules('report.create'));
        $user_id         =  auth()->user()->id;

        $reports         =  Reports::create([
                                'user_id'   => auth()->user()->id,
                                'title'     => $request->title,
                                'content'   => $request->content,
                               ]);


        $data            =  $reports;
        $message         =  248;
        $message_code    = -1  ;


        $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
        $fcm             = new FCM();

        foreach ($observers_token as $token) {
            $fcm->to($token)
                ->message($request->content, 'بلاغ جديد بعنوان: ' . $request->title )
                ->data(0, 'new_report', $request->content, 'بلاغ جديد بعنوان: ' . $request->title,  'ReportsPage')
                ->send();
        }

        return response()->data($data ,$message,$message_code);

    }

    public function reports()
    {
        $id         =  auth()->user()->id;

        $reports = Reports::with('user:id,username,avatar,role,country_id,deleted_at','user.country:id,code')->orderBy('created_at', 'desc')->get();

        $reports = $reports->map(function ($item) use ($id) {

            $item->count_not_seen   = Chat::where('user_id'     , strtolower($item->user->role) == 'user'     ? $item->user->id : $id)
                                          ->Where('provider_id' , strtolower($item->user->role) == 'provider' ? $item->user->id : $id)
                                          ->where('send_by'     , $item->user->id)
                                          ->where('seen'        , 0)->count('id');
            
            $item->user->username = $item->user->username . ($item->user->deleted_at? ' (حساب محذوف)' : '');

            return $item;
        });

        $reports = $reports->sortBy([
            ['count_not_seen' , 'desc'],
            ['solved'         , 'asc'],
        ]);

        $count            =  count($reports->where("count_not_seen", '>' ,0));
        $count_not_solved =  Reports::where('solved', 0)->count('id') + $count;

        $data =
        [
            'count_not_solved'  => $count_not_solved,
            'reports'           => $reports,
        ];

        return response()->data($data);
    }
    public function changeStatus(Request  $request)
    {
        $reports = Reports::where('id', (int)$request->id)->update(['solved'=> (int)$request->solved]);

        return response()->data('status of raport was changed');
    }

    public function delete(Reports $report)
    {
        $report->delete();
        return response()->data([
            'success' => true
        ]);
    }

    public function status(string $status)
    {
        $reports = Reports::status($status)->with([
            'user' => function ($q) {
                $q->select('id', 'username', 'avatar');
            }
        ])->paginate();
        return response()->data([
            'reports' => $reports
        ]);
    }

    public function updateStatus(Request $request, Reports $report)
    {
        $data = $request->validate(rules('reports.status.update'));

        $report->update([
            'solved' => $data['status'] === ReportStatus::RESOLVED
        ]);

        return response()->data(
            $report
        );
    }
}
