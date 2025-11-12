<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Exports\UsersTemplateExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->company()->where('role', '!=', 'superadmin')->paginate(20);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::company()->get();
        return view('admin.user.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'string'],
            'functions' => ['nullable', 'array'],
            'visible_company' => ['array', 'min:1'],
            'admin_functions' => ['required', 'array'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role.required' => 'Please select a role.',
            'visible_company.required' => 'Please select at least one visible company.',
            'visible_company.min' => 'Please select at least one visible company.',
            'admin_functions.required' => 'Please select at least one admin function.',
        ]);

        // Generate a random password for the user
        // $password = Str::random(12);

        // Create the user
        $user = User::create([
            'name' => explode('@', $validatedData['email'])[0],
            'email' => $validatedData['email'],
            // 'password' => Hash::make($password),
            'company_id' => Auth::user()->company_id,
            'role' => $validatedData['role'],
            'functions' => $validatedData['functions'] ?? [],
            'visible_company_ids' => $validatedData['visible_company'] ?? [],
            'admin_functions' => $validatedData['admin_functions'] ?? [],
        ]);

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
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $companies = Company::company()->get();
        return view('admin.user.edit', compact('user', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'role' => ['required', 'string'],
            'functions' => ['nullable', 'array'],
            'visible_company' => ['array', 'min:1'],
            'admin_functions' => ['nullable', 'array'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role.required' => 'Please select a role.',
            'visible_company.required' => 'Please select at least one visible company.',
            'visible_company.min' => 'Please select at least one visible company.',
        ]);

        // Update the user
        $user->update([
            'name' => explode('@', $validatedData['email'])[0],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'functions' => $validatedData['functions'] ?? [],
            'visible_company_ids' => $validatedData['visible_company'] ?? [],
            'admin_functions' => $validatedData['admin_functions'] ?? [],
        ]);

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
}
