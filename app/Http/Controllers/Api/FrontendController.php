<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarDetail;


class FrontendController extends Controller
{
    public function getAllMakes(Request $request)
    {
        try {


            $cardetail = CarDetail::select('make','coverImage')->distinct()->get();

            return response()->json([
                'success' => 200,
                'message' => 'Brands retrieved successfully',
                'data' => $cardetail
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getAllModels(Request $request)
    {
        try {
            $cardetail = CarDetail::where('make', $request->make)->select('model')->distinct()->get();

            return response()->json([
                'success' => 200,
                'message' => 'Models retrieved successfully',
                'data' => $cardetail
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getAllCars(Request $request)
    {
        try {
            $cardetail = CarDetail::whereNull('deleted_at')->get();
            
            if ($request->has('make') && $request->make != '') {
                $cardetail = $cardetail->where('make', $request->make);
            }

            if ($request->has('model') && $request->model != '') {
                $cardetail = $cardetail->where('model', $request->model);
            }

            if ($request->has('min_price') && $request->min_price != '') {
                $cardetail = $cardetail->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price != '') {
                $cardetail = $cardetail->where('price', '<=', $request->max_price);
            }

            if ($request->has('body') && $request->body != '') {
                $cardetail = $cardetail->where('body', $request->body);
            }

            return response()->json([
                'success' => 200,
                'message' => 'Cars retrieved successfully',
                'data' => $cardetail
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    } 
    
    public function getCarById(Request $request)
    {
        try {
            $cardetail = CarDetail::find($request->id);

            return response()->json([
                'success' => 200,
                'message' => 'Cars retrieved successfully',
                'data' => $cardetail
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
