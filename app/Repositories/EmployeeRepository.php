<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class EmployeeRepository
{
    /**
     * Build the query for employees with optional filters.
     *
     * @param array $filters
     * @return Builder
     */
    public function getFilteredQuery(array $filters = []): Builder
    {
        $query = Employee::query();

        if (!empty($filters['position'])) {
            $query->where('position', $filters['position']);
        }
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    /**
     * Get paginated employees based on filters.
     */
    public function getPaginated(array $filters, int $perPage = 10)
    {
        return $this->getFilteredQuery($filters)->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Get all employees based on filters for export.
     */
    public function getAll(array $filters)
    {
        return $this->getFilteredQuery($filters)->latest()->get();
    }

    public function create(array $data)
    {
        return Employee::create($data);
    }

    public function update(Employee $employee, array $data)
    {
        return $employee->update($data);
    }

    public function delete(Employee $employee)
    {
        return $employee->delete();
    }
}
