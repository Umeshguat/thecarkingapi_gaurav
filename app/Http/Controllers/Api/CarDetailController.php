<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CarDetailController extends Controller
{
    public function index(Request $request)
    {
        try {
            $carDetails = CarDetail::get();

            return response()->json([
                'success' => true,
                'message' => 'Car details retrieved successfully',
                'data' => $carDetails
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching car details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $carDetail = CarDetail::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Car detail retrieved successfully',
                'data' => $carDetail
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Car detail not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching car detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
             $validatedData = $request->validate([
                'carName' => 'required|string|max:255',
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'body' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'fuelType' => 'required|string|max:255',
                'transmission' => 'nullable|string|max:255',
                'color' => 'required|string|max:255',
                'kilometers' => 'nullable|string|max:255',
                'km' => 'nullable|string|max:255',
                'owner' => 'required|string|max:255',
                'mfg' => 'nullable|string|max:255',
                'insurance' => 'nullable|string|max:255',
                'pollution' => 'nullable|string|max:255',
                'registration' => 'required|string|max:255',
                'features' => 'nullable|array',
                'coverImage' => 'nullable|string',
                'galleryImages' => 'nullable|array',
                'galleryImages.*' => 'string',
                'status' => 'nullable|string|max:255',
            ]);
            
            $carDetail = CarDetail::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Car detail created successfully',
                'data' => $carDetail
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating car detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            
            
            $carDetail = CarDetail::find($id);
            $carDetail->carName = $request->carName;
            $carDetail->make = $request->make ?? $carDetail->make;
            $carDetail->model = $request->model ?? $carDetail->model;
            $carDetail->body = $request->body ?? $carDetail->body;
            $carDetail->price = $request->price ?? $carDetail->price;
            $carDetail->year = $request->year ?? $carDetail->year;
            $carDetail->fuelType = $request->fuelType ?? $carDetail->fuelType;
            $carDetail->transmission = $request->transmission ?? $carDetail->transmission;
            $carDetail->color = $request->color ?? $carDetail->color;
            $carDetail->kilometers = $request->kilometers ?? $carDetail->kilometers;
            $carDetail->km = $request->km ?? $carDetail->km;
            $carDetail->owner = $request->owner ?? $carDetail->owner;
            $carDetail->mfg = $request->mfg ?? $carDetail->mfg;
            $carDetail->insurance = $request->insurance ?? $carDetail->insurance;
            $carDetail->pollution = $request->pollution ?? $carDetail->pollution;
            $carDetail->registration = $request->registration ?? $carDetail->registration;
            $carDetail->features = $request->features ?? $carDetail->features;
            $carDetail->coverImage = $request->coverImage ?? $carDetail->coverImage;
            $carDetail->galleryImages = $request->galleryImages ?? $carDetail->galleryImages;
            $carDetail->status = $request->status ?? $carDetail->status;
            $carDetail->save();

            return response()->json([
                'success' => true,
                'message' => 'Car detail updated successfully',
                'data' => $carDetail
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Car detail not found'
            ], 404);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating car detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $carDetail = CarDetail::findOrFail($id);
            $carDetail->deleted_at = now();
            $carDetail->save();

            return response()->json([
                'success' => true,
                'message' => 'Car detail deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Car detail not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting car detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function uploadCarImage(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'images'   => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $images = $validatedData['images'];
            $imageUrls = [];
            foreach ($images as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('car_images'), $imageName);
                $imageUrls[] = url('car_images/' . $imageName);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => ['image_urls' => $imageUrls]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteCarImage(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'image_url' => 'required|string',
            ]);

            $imageUrl = $validatedData['image_url'];

            // Extract filename from the URL
            $parsedUrl = parse_url(str_replace(' ', '%20', $imageUrl));
            $path = $parsedUrl['path'] ?? '';
            $filename = urldecode(basename($path));

            $filePath = public_path('car_images/' . $filename);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found',
                ], 404);
            }

            unlink($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
                'data' => ['image_url' => $imageUrl]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
