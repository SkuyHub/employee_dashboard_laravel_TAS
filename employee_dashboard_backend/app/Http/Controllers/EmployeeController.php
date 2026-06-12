<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $sortField = in_array($request->sort_by, [
            'name', 'email', 'position', 'department', 'salary', 'hired_at', 'status'
        ]) ? $request->sort_by : 'created_at';

        $sortDirection = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $employees = $query->orderBy($sortField, $sortDirection)->paginate(10);
        return EmployeeResource::collection($employees);
    }
 
}
