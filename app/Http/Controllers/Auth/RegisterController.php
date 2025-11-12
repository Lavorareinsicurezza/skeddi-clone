<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'vat_number' => ['required', 'string', 'max:50', 'unique:companies,vat_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'legal_address_street' => ['required', 'string', 'max:255'],
            'legal_address_number' => ['required', 'string', 'max:20'],
            'legal_address_postal' => ['required', 'string', 'max:20'],
            'legal_address_city' => ['required', 'string', 'max:100'],
            'operating_address_street' => ['required', 'string', 'max:255'],
            'operating_address_number' => ['required', 'string', 'max:20'],
            'operating_address_postal' => ['required', 'string', 'max:20'],
            'operating_address_city' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ]);

        DB::beginTransaction();

        try {
            // Create the company
            $company = Company::create([
                'name' => $validatedData['company_name'],
                'vat_number' => $validatedData['vat_number'],
                'phone' => $validatedData['phone'],
                'legal_address_street' => $validatedData['legal_address_street'],
                'legal_address_number' => $validatedData['legal_address_number'],
                'legal_address_postal' => $validatedData['legal_address_postal'],
                'legal_address_city' => $validatedData['legal_address_city'],
                'operating_address_street' => $validatedData['operating_address_street'],
                'operating_address_number' => $validatedData['operating_address_number'],
                'operating_address_postal' => $validatedData['operating_address_postal'],
                'operating_address_city' => $validatedData['operating_address_city'],
            ]);

            // Create the user (company owner)
            $user = User::create([
                'name' => $validatedData['owner_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'company_id' => $company->id,
                'role' => 'superadmin', // Set as admin/owner
            ]);

            DB::commit();

            // Log the user in
            Auth::login($user);

            // Store company_id in session for global scope
            session(['company_id' => $company->id]);

            return redirect('/')->with('success', 'Registration completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'An error occurred during registration. Please try again.',
            ]);
        }
    }
}
