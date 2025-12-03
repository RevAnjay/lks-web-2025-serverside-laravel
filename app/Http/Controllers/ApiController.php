<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\InstallmentResource;
use App\Models\AvailableMonth;
use App\Models\InstallmentApplySocieties;
use App\Models\InstallmentCars;
use App\Models\Validation;
use Auth;
use Carbon\Traits\Timestamp;
use Illuminate\Http\Request;
use DB;

class ApiController extends Controller
{
    //

    public function storeValidation(ValidationRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            if (!Auth::check())
                return response()->json(['message' => 'Unauthorized user'], 401);

            $data = Auth::user();
            $validated['society_id'] = $data->id;

            Validation::create($validated);

            DB::commit();
            return response()->json([
                'message' => 'Request data validation sent successful'
            ], 200);
        } catch (\Throwable $thrw) {
            DB::rollBack();
            return response()->json(['message' => $thrw->getMessage()], 500);
        }
    }

    public function getValidation()
    {
        if (!Auth::check())
            return response()->json(['message' => 'Unauthorized user'], 401);

        return response()->json(Validation::where('society_id', Auth::user()->id)->get());
    }

    public function getAllValidation()
    {
        if (!Auth::check())
            return response()->json(['message' => 'Unauthorized user'], 401);

        return response()->json(Validation::all());
    }

    public function installmentCars()
    {
        if (!Auth::check())
            return response()->json(['message' => 'Unauthorized user'], 401);

        return response()->json(InstallmentResource::collection(InstallmentCars::with('brand', 'month')->get()));
    }

    public function installmentCarsId($id)
    {
        if (!Auth::check())
            return response()->json(['message' => 'Unauthorized user'], 401);

        return response()->json(new InstallmentResource(InstallmentCars::with('brand', 'month')->find($id)));
    }

    public function applications(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!Auth::check())
                return response()->json(['message' => 'Unauthorized user'], 401);

            $user = Auth::user();
            $val = Validation::where('society_id', $user->id)->first();
            if (!$val || $val->status !== 'accepted') {
                return response()->json(['message' => 'Your data validator must be accepted by validator before'], 401);
            }

            if (InstallmentApplySocieties::where('society_id', $user->id)->exists()) {
                return response()->json(['message' => 'Application for a instalment can only be once'], 401);
            }

            $car = InstallmentCars::find($request->installment_id);
            $term = AvailableMonth::where('car_id', $car->id)->where('months', $request->months)->first();
            if (!$term) {
                return response()->json(['message' => 'Invalid months for this car'], 422);
            }

            // hitung monthly
            $rate = $term->interest_rate ?? 0;
            $monthly = (int) ceil(($car->price * (1 + $rate)) / $term->months);

            // B5b: income check
            if (($val->income ?? 0) < $monthly) {
                return response()->json(['message' => 'Income is less than required monthly payment'], 422);
            }


            InstallmentApplySocieties::create([
                'society_id' => $user->id,
                'installment_id' => $car->id,
                'avaliable_month_id' => $term->months,
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json(['message' => 'Application for installment car submitted successfully'], 200);

        } catch (\Throwable $thrw) {
            DB::rollBack();
            return response()->json(['message' => $thrw->getMessage()], 500);
        }
    }

    public function applicationsList()
    {
        if (!Auth::check())
            return response()->json(['message' => 'Unauthorized user'], 401);

        $user = Auth::user();

        return response()->json(ApplicationResource::collection(InstallmentApplySocieties::where('society_id', $user->id)->get()));
    }
}
