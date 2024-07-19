<?php

namespace App\Http\Controllers;

use App\Models\Reivew;
use App\Models\User;
use App\Models\grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Session;

class TabController extends Controller
{
    public function index($zone)
    {
        return view('tab', compact('zone'));
    }
    public function loginTab(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
            'zone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Получение входных данных из запроса
        $code = $request->input('code');
        $zone = $request->input('zone');
        switch ($zone) {
            case 'pvl':
                $zone = 14;
                break;
        }

        $user = User::select('users.id as user_id', 'users.name', 'users.photo', 'azs.id as azs_id', 'azs.address', 'azs.name as azs_name', 'azs.time as azs_time')->
            join('azs_to_user', 'azs_to_user.user_id', '=', 'users.id')->
            join('azs', 'azs.id', '=', 'azs_to_user.azs_id')->
            where('users.code', $code)->where('azs.zone', $zone)->first();
        if ($user) {
            return response()->json([
                'user_id' => $user->user_id,
                'azs_id' => $user->azs_id,
                'azs_name' => $user->azs_name,
                'address' => $user->address,
                'name' => $user->name,
                'photo' => $user->photo,
                'azs_time' => $user->azs_time
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function checkSession()
    {
        \Log::info('Session Data on check:', Session::all());
        if (Session::has('user_id')) {
            return response()->json([
                'user_id' => Session::get('user_id'),
                'azs_name' => Session::get('azs_name'),
                'adress' => Session::get('adress'),
                'name' => Session::get('name'),
                'photo' => Session::get('photo'),
            ]);
        } else {
            // return response()->json(Session::all());
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function qr($user_id)
    {
        $user = User::select('users.id as user_id', 'users.name', 'users.photo', 'azs.id as azs_id', 'azs.address', 'azs.name as azs_name', 'azs.zone')->
            join('azs_to_user', 'azs_to_user.user_id', '=', 'users.id')->
            join('azs', 'azs.id', '=', 'azs_to_user.azs_id')->
            find($user_id);
        return view('qr', compact('user'));
    }
    public function grade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'azs_id' => 'required|int',
            'user_id' => 'required|int',
            'grade' => 'required|int',
            'photo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['azs_id', 'user_id', 'grade']);

        // Сохранение фото
        $photo = $request->input('photo');
        $photo = str_replace('data:image/jpeg;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $photoName = 'grade/' . uniqid() . '.jpg';
        Storage::disk('public')->put($photoName, base64_decode($photo));

        // Сохранение записи в базу данных
        $grade = new grade();
        $grade->azs_id = $data['azs_id'];
        $grade->user_id = $data['user_id'];
        $grade->grade = $data['grade'];
        $grade->photo = $photoName;
        $grade->save();

        return response()->json($grade, 200);
    }

    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'azs_id' => 'required|int',
            'user_id' => 'required|int',
            'grade' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $review = new Reivew();
        $review->name = $data['name'];
        $review->phone = $data['phone'];
        $review->azs_id = $data['azs_id'];
        $review->user_id = $data['user_id'];
        $review->grade = $data['grade'];
        if ($data['comment'])
            $review->comment = $data['comment'];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName);
            $review->file = $filePath;
        }
        $review->save();
        if ($review)
            return response()->json(true, 200);
        else
            return response(500)->json(['error' => 'error'], 500);
    }
}
