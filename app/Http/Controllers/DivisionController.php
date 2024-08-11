<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Division::query();

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            $divisions = $query->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Data divisi berhasil diambil',
                'data' => [
                    'divisions' => $divisions->items(),
                ],
                'pagination' => [
                    'current_page' => $divisions->currentPage(),
                    'last_page' => $divisions->lastPage(),
                    'per_page' => $divisions->perPage(),
                    'total' => $divisions->total(),
                    'from' => $divisions->firstItem(),
                    'to' => $divisions->lastItem(),
                    'next_page_url' => $divisions->nextPageUrl(),
                    'prev_page_url' => $divisions->previousPageUrl(),
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Data divisi tidak ditemukan',
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
}