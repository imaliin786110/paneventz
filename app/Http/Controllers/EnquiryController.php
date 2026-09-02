<?php

namespace App\Http\Controllers;

use App\Mail\NewEnquiryNotification;
use App\Models\Enquiry;
use App\Models\WebsiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:50',
            'email'            => 'nullable|email|max:255',
            'wedding_date'     => 'nullable|date',
            'wedding_location' => 'nullable|string|max:255',
            'service'          => 'nullable|string|max:255',
            'message'          => 'nullable|string|max:3000',
        ]);

        $enquiry = Enquiry::create([
            'name'             => $validated['name'],
            'phone'            => $validated['phone'],
            'email'            => $validated['email'] ?? null,
            'wedding_date'     => $validated['wedding_date'] ?? null,
            'wedding_location' => $validated['wedding_location'] ?? null,
            'service'          => $validated['service'] ?? null,
            'message'          => $validated['message'] ?? null,
            'status'           => 'new',
        ]);

        // Send instant email notification to studio owner
        try {
            $recipients = array_values(array_unique(array_filter([
                'imaliinmirza@gmail.com',
                WebsiteSetting::first()?->email,
            ])));

            Mail::to($recipients)->send(new NewEnquiryNotification($enquiry));
        } catch (\Throwable $e) {
            Log::error('Failed to send new enquiry notification email: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! We have received your inquiry and will be in touch shortly.',
                'enquiry_id' => $enquiry->id,
            ]);
        }

        return redirect()->back()->with('success', 'Thank you! We have received your inquiry and will be in touch shortly.');
    }
}