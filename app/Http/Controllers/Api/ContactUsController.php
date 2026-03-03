<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ContactUsController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:20',
                'whatsapp_number' => 'nullable|string|max:20',
                'service_package' => 'required|string|max:255',
                'location' => 'required|string|max:255',
            ]);

            $contactUs = ContactUs::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Contact us form submitted successfully',
                'data' => $contactUs
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
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}