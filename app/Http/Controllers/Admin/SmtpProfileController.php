<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpProfile;
use Illuminate\Http\Request;

class SmtpProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smtpProfiles = SmtpProfile::paginate(20);
        return view('admin.smtp-profiles.index', compact('smtpProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.smtp-profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'reply_to' => 'nullable|email|max:255',
            'encryption' => 'required|in:tls,ssl,none',
        ]);

        // Convert 'none' to null for encryption
        if ($validated['encryption'] === 'none') {
            $validated['encryption'] = null;
        }

        SmtpProfile::create($validated);

        return redirect()->route('admin.smtp-profiles.index')->with('success', 'SMTP Profile created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $smtpProfile = SmtpProfile::findOrFail($id);
        return view('admin.smtp-profiles.show', compact('smtpProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $smtpProfile = SmtpProfile::findOrFail($id);
        return view('admin.smtp-profiles.edit', compact('smtpProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $smtpProfile = SmtpProfile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'reply_to' => 'nullable|email|max:255',
            'encryption' => 'required|in:tls,ssl,none',
        ]);

        // Convert 'none' to null for encryption
        if ($validated['encryption'] === 'none') {
            $validated['encryption'] = null;
        }

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $smtpProfile->update($validated);

        return redirect()->route('admin.smtp-profiles.index')->with('success', 'SMTP Profile updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $smtpProfile = SmtpProfile::findOrFail($id);
        $smtpProfile->delete();

        return redirect()->route('admin.smtp-profiles.index')->with('success', 'SMTP Profile deleted successfully');
    }
}
