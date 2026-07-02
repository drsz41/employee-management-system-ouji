<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['position', 'department', 'status']);
        $employees = $this->employeeService->getPaginatedEmployees($filters);

        return inertia('Dashboard/Employee/Index', [
            'employees' => $employees,
            'filters' => $filters
        ]);
    }

    public function exportCsv(Request $request)
    {
        $filters = $request->only(['position', 'department', 'status']);
        return $this->employeeService->exportCsv($filters);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        $this->employeeService->createEmployee($validated);

        return redirect()->back()->with('success', 'Employee created successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        $this->employeeService->updateEmployee($employee, $validated);

        return redirect()->back()->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->employeeService->deleteEmployee($employee);

        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
}
