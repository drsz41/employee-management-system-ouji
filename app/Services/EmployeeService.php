<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;
use App\Models\Employee;

class EmployeeService
{
    protected $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedEmployees(array $filters)
    {
        return $this->repository->getPaginated($filters);
    }

    public function createEmployee(array $data)
    {
        $data['employee_id'] = 'EMP-' . date('Y') . '-' . str_pad(Employee::count() + 1, 3, '0', STR_PAD_LEFT);
        return $this->repository->create($data);
    }

    public function updateEmployee(Employee $employee, array $data)
    {
        return $this->repository->update($employee, $data);
    }

    public function deleteEmployee(Employee $employee)
    {
        return $this->repository->delete($employee);
    }

    public function exportCsv(array $filters)
    {
        $employees = $this->repository->getAll($filters);

        $filename = "employees_export_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Employee ID', 'Name', 'Email', 'Position', 'Department', 'Status', 'Join Date'];

        $callback = function() use($employees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->id,
                    $employee->employee_id,
                    $employee->name,
                    $employee->email,
                    $employee->position,
                    $employee->department,
                    $employee->status,
                    $employee->join_date
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
