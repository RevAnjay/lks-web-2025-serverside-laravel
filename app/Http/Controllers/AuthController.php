<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_card_number' => 'required|exists:societies,id_card_number',
                'password' => 'required'
            ]);

            if ($validator->fails())
                return response()->json(["message" => "ID Card Number or Password incorrect"], 401);

            $data = Society::where('id_card_number', $request->id_card_number)->with('Regional')->first();

            if (!$data || $data->password != $request->password)
                return response()->json(["message" => "ID Card Number or Password incorrect"], 401);

            return response()->json([
                "id" => $data->id,
                "name" => $data->name,
                "born_date" => $data->born_date,
                "gender" => $data->gender,
                "address" => $data->address,
                "token" => $data->createToken('auth-token')->plainTextToken,
                "regional" => $data->regional
            ]);
        } catch (\Throwable $thrw) {
            return response()->json(['message' => $thrw->getMessage()], 400);
        }
    }

    public function logout()
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user)
                return response()->json(['message' => 'Invalid token']);

            $user->currentAccessToken()->delete();

            DB::commit();
            return response()->json([
                'message' => 'Logout success'
            ]);
        } catch (\Throwable $thrw) {
            DB::rollBack();
            return response()->json(['message' => $thrw]);
        }
    }

    public function user()
    {
        try {
            $user = Auth::user();

            return response()->json($user);
        } catch (\Throwable $thrw) {
            return response()->json(['message' => $thrw]);
        }
    }
}
