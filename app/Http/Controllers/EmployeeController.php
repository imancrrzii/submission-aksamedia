<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with('division');

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->has('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $employees = $query->paginate(10);

            $employeesCollection = collect($employees->items());
            $transformedEmployees = $employeesCollection->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'image' => $employee->image,
                    'name' => $employee->name,
                    'phone' => $employee->phone,
                    'division' => [
                        'id' => $employee->division->id,
                        'name' => $employee->division->name
                    ],
                    'position' => $employee->position,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data fetched successfully',
                'data' => [
                    'employees' => $transformedEmployees,
                ],
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                    'from' => $employees->firstItem(),
                    'to' => $employees->lastItem(),
                    'next_page_url' => $employees->nextPageUrl(),
                    'prev_page_url' => $employees->previousPageUrl(),
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|url',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'division' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            Employee::create([
                'id' => (string) Str::uuid(),
                'image' => $request->input('image'),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'division_id' => $request->input('division'),
                'position' => $request->input('position'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil dibuat',
            ], 201);
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat data',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga',
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::where('id', $id)->firstOrFail();

            $validator = Validator::make($request->all(), [
                'image' => 'nullable|url',
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'division' => 'nullable|exists:divisions,id',
                'position' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $employee->update([
                'image' => $request->input('image', $employee->image),
                'name' => $request->input('name', $employee->name),
                'phone' => $request->input('phone', $employee->phone),
                'division_id' => $request->input('division', $employee->division_id),
                'position' => $request->input('position', $employee->position),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil diperbarui',
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Data karyawan tidak ditemukan',
            ], 404);
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga',
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $employee = Employee::where('id', $id)->firstOrFail();
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil dihapus',
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Data karyawan tidak ditemukan',
            ], 404);
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga',
            ], 500);
        }
    }
}



