<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;

class RoleTestController extends Controller
{
    // --- FITUR 1: MARKETING (Membuat Iklan) ---
    public function createCampaign()
    {
        try {
            // Ini akan otomatis pakai user 'marketing_user' sesuai setting di Model
            Campaign::create([
                'campaign_name' => 'Promo Liburan ' . rand(1, 100),
                'budget' => 5000000,
                'target_audience' => 'Remaja Gen-Z',
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+30 days'))
            ]);

            return "SUKSES: Marketing berhasil membuat Campaign baru!";
        } catch (\Exception $e) {
            return "ERROR Marketing: " . $e->getMessage();
        }
    }

    // --- FITUR 2: EXECUTIVE (Approve Film) ---
    public function approveShow($tconst)
    {
        // $tconst contoh: 'tt0096697' (The Simpsons)
        
        try {
            // KITA SWITCH KONEKSI SECARA MANUAL DI SINI
            // Gunakan koneksi 'sqlsrv_executive' khusus untuk aksi ini
            $affected = DB::connection('sqlsrv_executive')
                ->table('tv_shows')
                ->where('tconst', $tconst)
                ->update(['id_status' => 2]); // Anggap 2 = Approved

            if ($affected) {
                return "SUKSES: Executive berhasil Approve show $tconst.";
            } else {
                return "Data tidak ditemukan atau status sudah sama.";
            }
        } catch (\Exception $e) {
            return "ERROR Executive: " . $e->getMessage();
        }
    }

    // --- FITUR 3: TEST KEAMANAN (Marketing Coba Hapus Film) ---
    public function marketingTryHack()
    {
        try {
            // Marketing coba akses tabel 'tv_shows' lewat koneksi mereka
            DB::connection('sqlsrv_marketing')
                ->table('tv_shows')
                ->where('tconst', 'tt0096697')
                ->delete();

            return "BAHAYA: Marketing BERHASIL menghapus film! (Security Gagal)";
        } catch (\Exception $e) {
            return "AMAN: Marketing GAGAL menghapus film. <br>Pesan Error DB: " . $e->getMessage();
        }
    }
}