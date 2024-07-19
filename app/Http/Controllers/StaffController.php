<?php

namespace App\Http\Controllers;

use App\Models\azs_to_user;
use App\Models\Reivew;
use App\Models\User;
use App\Models\grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $request->validate([
            'azs' => 'nullable|int|max:11',
            'search' => 'nullable|string|max:255',
        ]);

        $filter_azs = $request->input('azs', '');
        $search = $request->input('search', '');

        $users = User::select('users.*', 'azs.name as azs_name', 'azs.delete as azs_delete')
            ->join('azs_to_user', 'azs_to_user.user_id', '=', 'users.id')
            ->join('azs', 'azs.id', '=', 'azs_to_user.azs_id')
            ->where('users.id', '<>', auth()->user()->id)
            ->where('users.role', '<>', 1);

        if (!empty($search)) {
            $users->where('users.name', 'LIKE', '%' . $search . '%');
        }

        if (!empty($filter_azs)) {
            $users->where('azs.id', '=', $filter_azs);
        }

        $users = $users->paginate(10)->appends($request->query());

        foreach ($users as $key => $user) {
            if (!$user->photo)
                $user->photo = 'pupil.png';
            $users[$key]['avg_grade'] = round(grade::where('user_id', '=', $user->id)->avg('grade'), 1);
        }

        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return view('staff', compact('users', 'search', 'filter_azs', 'token'));
    }

    public function review($staff, Request $request)
    {
        $review = Reivew::where('user_id', '=', $staff)->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        return view('review', compact('review'));
    }

    public function staff(Request $request)
    {
        $request->validate([
            'id' => 'required|int|max:255',
        ]);

        $staff = User::find($request->id);
        if ($staff) {
            $staff['azs'] = azs_to_user::where('user_id', '=', $request->id)->first()->azs_id;
            return response()->json($staff);
        } else
            return response()->json(['error' => '404 Not Found'], 404);
    }
    public function getAllStaff(Request $request)
    {
        $azsId = $request->azs_id;

        if ($azsId) {
            $employees = User::select('users.*')->join('azs_to_user', 'azs_to_user.user_id', '=', 'users.id')->where('azs_id', $azsId)->get();
        } else {
            $employees = User::all();
        }

        return response()->json([
            'employees' => $employees,
            'allEmployees' => User::all()
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|exists:role,id',
            'phone' => 'required|string|max:255',
            'azs' => 'required|exists:azs,id',
        ]);

        do {
            $code = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (User::where('code', $code)->exists());

        try {
            $user = new User();
            $user->name = $request->name;
            $user->role = $request->role;
            $user->phone = $request->phone;
            $user->password = Hash::make($code);
            $user->code = $code;
            $user->save();

            $azs_to_user = new azs_to_user();
            $azs_to_user->azs_id = $request->azs;
            $azs_to_user->user_id = $user->id;
            $azs_to_user->save();

            return response()->json([
                'success' => 'Сотрудник успешно добавлен! Код для авторизации сотрудника: ' . $code,
                'code' => $code
            ]);
        } catch (\Exception $e) {
            return response('', 500)->json([
                'error' => 'Не удалось добавить сотрудника!',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'role' => 'required|exists:role,id',
            'phone' => 'required|string|max:255',
            'azs' => 'required|int|max:11',
        ]);

        try {
            $user = User::findOrFail($request->id);

            if ($request->azs != $user->azs) {
                $azs = azs_to_user::where('user_id', $user->id)->first();
                if ($azs) {
                    $azs->azs_id = $request->azs;
                    $azs->save();
                }
            }

            $user->name = $request->name;
            $user->role = $request->role;
            $user->phone = $request->phone;
            $user->save();

            return response()->json(['success' => 'Сотрудник успешно обновлен']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        try {
            $user = User::findOrFail($request->id);
            $user->delete = 1;
            $user->save();

            $azs_user = azs_to_user::where('user_id', '=', $user->id)->first();
            $azs_user->delete();

            return response()->json(['success' => 'АЗС успешно удалена']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }
    }

}
