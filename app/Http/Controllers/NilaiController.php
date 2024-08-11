<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class NilaiController extends Controller
{
    public function getNilaiST()
    {
        try {
            $results = DB::select("
                SELECT 
                    n.nama,
                    n.nisn,
                    SUM(
                        CASE 
                            WHEN n.pelajaran_id = 44 THEN n.skor * 41.67
                            WHEN n.pelajaran_id = 45 THEN n.skor * 29.67
                            WHEN n.pelajaran_id = 46 THEN n.skor * 100
                            WHEN n.pelajaran_id = 47 THEN n.skor * 23.81
                            ELSE 0
                        END
                    ) AS total_nilai_st,
                    SUM(
                        CASE 
                            WHEN n.pelajaran_id = 44 THEN n.skor * 41.67
                            ELSE 0
                        END
                    ) AS verbal,
                    SUM(
                        CASE 
                            WHEN n.pelajaran_id = 45 THEN n.skor * 29.67
                            ELSE 0
                        END
                    ) AS kuantitatif,
                    SUM(
                        CASE 
                            WHEN n.pelajaran_id = 46 THEN n.skor * 100
                            ELSE 0
                        END
                    ) AS penalaran,
                    SUM(
                        CASE 
                            WHEN n.pelajaran_id = 47 THEN n.skor * 23.81
                            ELSE 0
                        END
                    ) AS figural
                FROM 
                    nilai n
                WHERE 
                    n.materi_uji_id = 4
                GROUP BY 
                    n.nama, n.nisn
                ORDER BY 
                    total_nilai_st DESC;
            ");

            $data = collect($results)->map(function ($item) {
                $item->total_nilai_st = number_format($item->total_nilai_st, 2);
                return $item;
            });

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data Nilai ST: ' . $e->getMessage()], 500);
        }
    }

    public function getNilaiRT()
    {
        try {
            $results = DB::select("
                SELECT 
                n.nama,
                n.nisn,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Realistic' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS realistic,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Investigative' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS investigative,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Artistic' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS artistic,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Social' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS social,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Enterprising' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS enterprising,
                SUM(
                    CASE 
                        WHEN n.pelajaran_id = (
                            SELECT pelajaran_id 
                            FROM nilai 
                            WHERE nama_pelajaran = 'Conventional' LIMIT 1
                        ) AND n.nama_pelajaran <> 'Pelajaran Khusus'
                        THEN n.skor
                        ELSE 0
                    END
                ) AS conventional
            FROM 
                nilai n
            WHERE 
                n.materi_uji_id = 7
            GROUP BY 
                n.nama, n.nisn
            ORDER BY 
                n.nama;
            ");

            $data = collect($results)->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'nisn' => $item->nisn,
                    'realistic' => (int) $item->realistic,
                    'investigative' => (int) $item->investigative,
                    'artistic' => (int) $item->artistic,
                    'social' => (int) $item->social,
                    'enterprising' => (int) $item->enterprising,
                    'conventional' => (int) $item->conventional,
                ];
            });
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data Nilai RT: ' . $e->getMessage()], 500);
        }
    }
}
