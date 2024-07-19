<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\azs;
use App\Models\azs_to_user;
use App\Models\grade;
use App\Models\Zone;

class AZSContorller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $azs = azs::where('delete', '=', '0')->get();
        $token = auth()->user()->createToken('auth_token')->plainTextToken;
        foreach ($azs as $key => $a) {
            $zone = Zone::find($a->zone);
            $azs[$key]['zone_name'] = $zone != null && $zone->delete != 1 ? $zone->name : 'Удалён';
            $azs[$key]['count_staff'] = azs_to_user::join('users', 'users.id', '=', 'azs_to_user.user_id')->where('users.delete', '=', null)->where('azs_id', '=', $a->id)->count();
            $azs[$key]['avg_grade'] = round(grade::where('azs_id', '=', $a->id)->avg('grade'), 1);
        }
        return view('azs', compact('azs', 'token'));
        // return response()->json($azs);
    }
    public function azs(Request $request)
    {
        $azs = azs::find($request->id);
        return response()->json($azs);
    }

    public function staff(Request $request)
    {
        $staff = azs_to_user::select('users.*', 'role.name as role_name')->join('users', 'users.id', '=', 'azs_to_user.user_id')->join('role', 'role.id', '=', 'users.role')->where('azs_to_user.azs_id', '=', $request->id)->get();
        return response()->json($staff);
    }
    public function add(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'zone' => 'required|exists:zone,id',
            'address' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
        ]);

        try {
            // Создание новой записи
            $azs = new AZS();
            $azs->upr_id = 1;
            $azs->name = $request->name;
            $azs->zone = $request->zone;
            $azs->address = $request->address;
            $azs->time = $request->time;
            $azs->save();

            return response()->json(['success' => 'АЗС успешно добавлена!']);
        } catch (\Exception $e) {
            return response()->json(['success' => 'Не удалось добавить!', 'error' => $e->getMessage()]);
        }
    }

    public function edit(Request $request)
    {
        // Валидация данных
        $request->validate([
            'id' => 'required|exists:azs,id',
            'name' => 'required|string|max:255',
            'zone' => 'required|exists:zone,id',
            'address' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
        ]);

        try {
            $azs = azs::findOrFail($request->id);
            $azs->name = $request->name;
            $azs->zone = $request->zone;
            $azs->address = $request->address;
            $azs->time = $request->time;
            $azs->save();

            return response()->json(['success' => 'АЗС успешно обновлены']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:azs,id'
        ]);

        try {
            $azs = azs::findOrFail($request->id);
            $azs->delete = 1;
            $azs->save();

            return response()->json(['success' => 'АЗС успешно удалена']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }
    }
}
