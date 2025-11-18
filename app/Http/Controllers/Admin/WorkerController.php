<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workers = Worker::query()->company()->paginate(20);
        return view('company.worker.index', compact('workers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.worker.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'workplace_safety_risk' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'additional_information' => 'nullable|string',
            'worker_documentation' => 'nullable|string',
            'ppe' => 'nullable|string',
            'movement_history' => 'nullable|string',
            'training_experience' => 'nullable|string',
            'medical_visits' => 'nullable|string',
        ]);

        $companyId = session('selectedCompanyId');

        Worker::create([
            'company_id' => $companyId,
            'first_name' => $request->input('first_name'),
            'surname' => $request->input('surname'),
            'job_title' => $request->input('job_title'),
            'department' => $request->input('department'),
            'workplace_safety_risk' => $request->has('workplace_safety_risk') ? 1 : 0,
            'is_active' => $request->has('is_active') ? !$request->input('is_active') : true,
            'additional_information' => $request->input('additional_information'),
            'worker_documentation' => $request->input('worker_documentation'),
            'ppe' => $request->input('ppe'),
            'movement_history' => $request->input('movement_history'),
            'training_experience' => $request->input('training_experience'),
            'medical_visits' => $request->input('medical_visits'),
        ]);

        return redirect()->route('admin.company-workers.index')->with('success', __('lang.worker_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $worker = Worker::query()->company()->findOrFail($id);
        return view('company.worker.show', compact('worker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $worker = Worker::query()->company()->findOrFail($id);
        return view('company.worker.create', compact('worker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'workplace_safety_risk' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'additional_information' => 'nullable|string',
            'worker_documentation' => 'nullable|string',
            'ppe' => 'nullable|string',
            'movement_history' => 'nullable|string',
            'training_experience' => 'nullable|string',
            'medical_visits' => 'nullable|string',
        ]);

        $worker = Worker::query()->company()->findOrFail($id);
        $worker->update([
            'first_name' => $request->input('first_name'),
            'surname' => $request->input('surname'),
            'job_title' => $request->input('job_title'),
            'department' => $request->input('department'),
            'workplace_safety_risk' => $request->has('workplace_safety_risk') ? 1 : 0,
            'is_active' => $request->has('is_active') ? !$request->input('is_active') : true,
            'additional_information' => $request->input('additional_information'),
            'worker_documentation' => $request->input('worker_documentation'),
            'ppe' => $request->input('ppe'),
            'movement_history' => $request->input('movement_history'),
            'training_experience' => $request->input('training_experience'),
            'medical_visits' => $request->input('medical_visits'),
        ]);

        return redirect()->route('admin.company-workers.index')->with('success', __('lang.worker_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $worker = Worker::query()->company()->findOrFail($id);
        $worker->delete();

        return redirect()->route('admin.company-workers.index')->with('success', __('lang.worker_deleted_successfully'));
    }

    /**
     * Import workers from file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // TODO: Implement import logic
        return redirect()->route('admin.company-workers.index')->with('success', __('lang.workers_imported_successfully'));
    }
}
