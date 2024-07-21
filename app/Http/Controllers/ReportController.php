<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\azs;
use App\Models\azs_to_user;
use App\Models\grade;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $token = auth()->user()->createToken('auth_token')->plainTextToken;
        return view('report', compact('token'));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $select_azs = $request->input('azs');
        $staff = $request->input('staff');

        if ($select_azs) {
            $all_azs = azs::where('id', '=', $select_azs)->where('delete', '=', 0)->get();

            $azs = [];

            foreach ($all_azs as $az) {
                $users_azs = azs_to_user::select('users.*')->join('users', 'users.id', '=', 'azs_to_user.user_id')->where('users.delete', '=', null)->where('azs_to_user.azs_id', $az->id);
                if ($staff) {
                    $users_azs->whereIn('users.id', $staff);
                }
                $users_azs = $users_azs->get();
                foreach ($users_azs as $key => $azz) {
                    $users_azs[$key]['avg_grade'] = round(grade::where('azs_id', '=', $azz->id)->whereBetween('created_at', [$startDate, $endDate])->avg('grade'), 1);
                }

                $azs[$az['name']] = $users_azs;
            }
        } else {
            $all_azs = azs::where('delete', '=', 0)->get();

            $azs = [];

            foreach ($all_azs as $az) {
                $users_azs = azs_to_user::select('users.*')->join('users', 'users.id', '=', 'azs_to_user.user_id')->where('users.delete', '=', null)->where('azs_to_user.azs_id', $az->id);
                if ($staff) {
                    $users_azs->whereIn('users.id', $staff);
                }
                $users_azs = $users_azs->get();
                foreach ($users_azs as $key => $azz) {
                    $users_azs[$key]['avg_grade'] = round(grade::where('azs_id', '=', $azz->id)->whereBetween('created_at', [$startDate, $endDate])->avg('grade'), 1);
                }

                $azs[$az['name']] = $users_azs;
            }
        }

        $azs = array_filter($azs, function ($collection) {
            return $collection->isNotEmpty();
        });
        $pdf = PDF::loadView('reportPDF', compact('startDate', 'endDate', 'azs'));
        return $pdf->stream('report.pdf');
    }
}
