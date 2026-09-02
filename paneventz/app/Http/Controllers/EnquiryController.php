<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:50',
            'event_date'     => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'services'       => 'nullable|array',
            'budget'         => 'nullable|string|max:100',
            'message'        => 'nullable|string|max:3000',
        ]);

        $enquiry = Enquiry::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'event_date'     => $validated['event_date'] ?? null,
            'event_location' => $validated['event_location'] ?? null,
            'services'       => $validated['services'] ?? [],
            'budget'         => $validated['budget'] ?? null,
            'message'        => $validated['message'] ?? null,
            'status'         => 'new',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for reaching out! We have received your inquiry and will be in touch shortly.',
                'enquiry_id' => $enquiry->id,
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for reaching out! We have received your inquiry and will be in touch shortly.');
    }
}