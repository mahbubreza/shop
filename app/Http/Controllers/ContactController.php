<?php

// app/Http/Controllers/ContactController.php
namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $contact = ContactMessage::create($validated);

        // Optional email
        Mail::raw(
            "New Contact Message:\n\n"
            . "Name: {$validated['name']}\n"
            . "Email: {$validated['email']}\n"
            . "Subject: {$validated['subject']}\n\n"
            . "Message:\n{$validated['message']}",
            function ($mail) use ($validated) {
                $mail->to('admin@example.com')
                    ->subject('New Contact Message from ' . $validated['name']);
            }
        );

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '✅ Message sent successfully!',
                'data' => $contact,
            ]);
        }

        return back()->with('success', 'Message sent successfully!');
    }
}

