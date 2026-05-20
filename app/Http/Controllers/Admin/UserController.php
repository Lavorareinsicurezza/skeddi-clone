<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Exports\UsersTemplateExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name')->toArray();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'role.required' => 'Please select a role.',
        ]);

        $primaryRoleName = $validatedData['role'];

        $user = User::create([
            'name' => explode('@', $validatedData['email'])[0],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'company_id' => Auth::user()->company_id,
            'role' => $primaryRoleName,
        ]);

        $user->syncRoles([$primaryRoleName]);

        // TODO: Send email to user with credentials

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('company')->findOrFail($id);
        $userPermissions = $user->getPermissionsViaRoles()->pluck('name');
        return view('admin.user.show', compact('user', 'userPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name')->toArray();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'email'    => ['required', 'email', 'unique:users,email,' . $id],
            'role'     => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'email.required'  => 'Email address is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email address is already registered.',
            'role.required'   => 'Please select a role.',
            'password.min'    => 'Password must be at least 8 characters.',
        ]);

        $data = [
            'name'  => explode('@', $validatedData['email'])[0],
            'email' => $validatedData['email'],
            'role'  => $validatedData['role'],
        ];

        if (!empty($validatedData['password'])) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        $user->update($data);

        $user->syncRoles([$validatedData['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Export users to Excel
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Import users from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('admin.users.index')
                ->with('success', 'Users imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $row = $failure->row();
                $attribute = $failure->attribute();
                $errors = $failure->errors();
                $values = $failure->values();

                foreach ($errors as $error) {
                    $errorMessages[] = "Row {$row}: {$error}";
                }
            }

            $errorMessage = implode('<br>', $errorMessages);

            return redirect()->route('admin.users.index')
                ->with('error', 'Validation errors occurred during import:<br>' . $errorMessage);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Error importing users: ' . $e->getMessage());
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        return Excel::download(new UsersTemplateExport, 'users-import-template.xlsx');
    }

    /**
     * Send OTP for password reset
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store in Cache for 10 minutes
        Cache::put('password_reset_otp_' . $user->id, $otp, now()->addMinutes(10));

        // Send Email
        try {
            $this->sendEmail($user, 'Password Reset OTP', 'emails.otp', ['user' => $user, 'otp' => $otp]);
            return response()->json(['success' => true, 'message' => 'OTP sent successfully to ' . $user->email]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|digits:6',
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ]);

        $cachedOtp = Cache::get('password_reset_otp_' . $request->user_id);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        $user = User::findOrFail($request->user_id);

        // Update Password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear OTP
        Cache::forget('password_reset_otp_' . $request->user_id);

        // Send Confirmation Email
        try {
            $this->sendEmail($user, 'Password Changed Successfully', 'emails.password-changed', ['user' => $user]);
        } catch (\Exception $e) {
            // Log error but don't fail the request since password is changed
            // Log::error('Failed to send password changed email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Password changed successfully!']);
    }

    /**
     * Helper to send email using user's company settings
     */
    private function sendEmail($user, $subject, $viewName, $data)
    {
        $companyId = $user->company_id;

        // If user doesn't have company_id (e.g. super admin?), try Auth user's company or fail gracefully
        if (!$companyId) {
             throw new \Exception('User is not associated with a company for SMTP settings.');
        }

        $setting = Setting::where('company_id', $companyId)->first();

        if (!$setting || !$setting->smtp_host || !$setting->smtp_username || !$setting->smtp_password) {
            throw new \Exception('SMTP settings missing for company.');
        }

        $scheme = $setting->smtp_port == 465 ? 'smtps' : 'smtp';
        $dsn = sprintf(
            '%s://%s:%s@%s:%s',
            $scheme,
            urlencode($setting->smtp_username),
            urlencode($setting->smtp_password),
            $setting->smtp_host,
            $setting->smtp_port
        );

        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer(transport: $transport);

        $html = View::make($viewName, $data)->render();

        $fromAddress = $setting->smtp_address ?? $setting->smtp_username;
        $fromName = config('app.name');

        $email = (new Email())
            ->from(new Address($fromAddress, $fromName))
            ->to($user->email)
            ->subject($subject)
            ->html($html);

        if ($setting->smtp_reply_to) {
            $email->replyTo($setting->smtp_reply_to);
        }

        $mailer->send($email);
    }
}
