<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SpmbPendaftaran;
use App\Models\SpmbSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class SpmbController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(['driver' => 'gd']);
    }
    public function create()
    {
        $setting = SpmbSetting::where('is_active', true)->latest()->first();
        $isOpen = SpmbSetting::isOpen();

        $pendaftaran = SpmbPendaftaran::with([
            'siswa', 'ayah', 'ibu', 'wali', 'periodik', 'prestasi', 'beasiswa', 'berkas'
        ])->where('user_id', Auth::id())->latest()->first();

        return Inertia::render('Spmb/Register', [
            'existingData' => $pendaftaran,
            'spmbSetting' => [
                'isOpen' => $isOpen,
                'tahunAjaran' => $setting?->tahun_ajaran,
                'pesanTutup' => $setting?->pesan_tutup,
                'tglTutup' => $setting?->tgl_tutup?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if (!SpmbSetting::isOpen()) {
            return back()->withErrors(['message' => 'Mohon maaf, pendaftaran saat ini sedang ditutup.']);
        }

        $request->validate([
            'siswa.nama_lengkap' => 'required|string|max:255',
            'siswa.nik' => 'nullable|string|max:16',
            'siswa.nisn' => 'nullable|string|max:10',
            'siswa.alamat' => 'required|string',
            'siswa.provinsi' => 'required|string',
            'siswa.kabupaten_kota' => 'required|string',
            'siswa.kecamatan' => 'required|string',
            'siswa.kelurahan_desa' => 'required|string',
            'siswa.kode_pos' => 'nullable|string|max:10',
            'ayah.nama' => 'required|string|max:255',
            'ayah.nik' => 'nullable|string|max:16',
            'ibu.nama' => 'required|string|max:255',
            'ibu.nik' => 'nullable|string|max:16',
            'wali.nik' => 'nullable|string|max:16',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        ], [
            'siswa.nama_lengkap' => 'Nama Lengkap Siswa',
            'siswa.nik' => 'NIK Siswa',
            'siswa.nisn' => 'NISN',
            'siswa.alamat' => 'Alamat Lengkap',
            'siswa.provinsi' => 'Provinsi',
            'siswa.kabupaten_kota' => 'Kabupaten/Kota',
            'siswa.kecamatan' => 'Kecamatan',
            'siswa.kelurahan_desa' => 'Kelurahan/Desa',
            'siswa.kode_pos' => 'Kode Pos',
            'ayah.nama' => 'Nama Ayah',
            'ayah.nik' => 'NIK Ayah',
            'ibu.nama' => 'Nama Ibu',
            'ibu.nik' => 'NIK Ibu',
            'wali.nik' => 'NIK Wali',
        ]);

        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $pendaftaran = SpmbPendaftaran::where('user_id', $user_id)->first();
            $isNew = !$pendaftaran;

            if ($pendaftaran) {
                // --- UPDATE EXISTING ---
                $pendaftaran->update(['tingkat' => $request->tingkat]);

                $pendaftaran->siswa()->updateOrCreate(['pendaftaran_id' => $pendaftaran->id], $request->siswa);
                $pendaftaran->orangTua()->updateOrCreate(['pendaftaran_id' => $pendaftaran->id, 'jenis' => 'ayah'], $request->ayah);
                $pendaftaran->orangTua()->updateOrCreate(['pendaftaran_id' => $pendaftaran->id, 'jenis' => 'ibu'], $request->ibu);

                if (!empty($request->wali['nama'])) {
                    $pendaftaran->wali()->updateOrCreate(['pendaftaran_id' => $pendaftaran->id], $request->wali);
                } else {
                    $pendaftaran->wali()->delete();
                }

                $pendaftaran->periodik()->updateOrCreate(['pendaftaran_id' => $pendaftaran->id], $request->periodik);

                // Clear list items to recreate them
                $pendaftaran->prestasi()->delete();
                $pendaftaran->beasiswa()->delete();

            } else {
                // --- CREATE NEW ---
                $pendaftaran = SpmbPendaftaran::create([
                    'user_id' => $user_id,
                    'no_reg' => 'REG-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                    'tanggal_daftar' => now(),
                    'tingkat' => $request->tingkat,
                    'status' => 'pending',
                ]);

                $pendaftaran->siswa()->create($request->siswa);
                $pendaftaran->orangTua()->create(array_merge($request->ayah, ['jenis' => 'ayah']));
                $pendaftaran->orangTua()->create(array_merge($request->ibu, ['jenis' => 'ibu']));

                if (!empty($request->wali['nama'])) {
                    $pendaftaran->wali()->create($request->wali);
                }
                $pendaftaran->periodik()->create($request->periodik);
            }

            // Shared logic for lists
            if (!empty($request->prestasi)) {
                foreach ($request->prestasi as $item) {
                    if (!empty($item['nama_prestasi'])) $pendaftaran->prestasi()->create($item);
                }
            }

            if (!empty($request->beasiswa)) {
                foreach ($request->beasiswa as $item) {
                    if (!empty($item['jenis_beasiswa'])) $pendaftaran->beasiswa()->create($item);
                }
            }

            // Simpan Berkas
            $berkasData = [];
            $fileFields = [
                'ijazah_skhu', 'akte_kelahiran', 'ktp_orang_tua', 'kartu_keluarga',
                'surat_sehat', 'surat_kelakuan_baik', 'kartu_kks_pkh', 'kartu_kps',
                'kartu_kip', 'kartu_kis_bpjs', 'kartu_nisn'
            ];

            $studentName = \Illuminate\Support\Str::slug($request->siswa['nama_lengkap'] ?? 'santri');

            foreach ($fileFields as $field) {
                if ($request->hasFile("berkas.$field")) {
                    $file = $request->file("berkas.$field");
                    $extension = $file->getClientOriginalExtension();
                    
                    // Jika file adalah gambar, kita optimasi
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fileName = "{$field}_{$studentName}.webp"; // Simpan sebagai webp untuk hemat ruang
                        $path = "berkas/{$pendaftaran->no_reg}/{$fileName}";
                        
                        $image = $this->imageManager->make($file);
                        
                        // Resize jika terlalu lebar
                        if ($image->width() > 1200) {
                            $image->resize(1200, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                        }
                        
                        // Encode ke webp dengan kualitas 80%
                        $encoded = $image->encode('webp', 80);
                        
                        Storage::disk('public')->put($path, $encoded);
                    } else {
                        // Jika bukan gambar (misal PDF), simpan biasa
                        $fileName = "{$field}_{$studentName}.{$extension}";
                        $path = $file->storeAs("berkas/{$pendaftaran->no_reg}", $fileName, 'public');
                    }
                    
                    $berkasData[$field] = $path;
                }
            }

            if (!empty($berkasData)) {
                if ($pendaftaran->berkas) {
                    $pendaftaran->berkas()->update($berkasData);
                } else {
                    $pendaftaran->berkas()->create($berkasData);
                }
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Gagal menyimpan pendaftaran: ' . $e->getMessage()]);
        }
    }

    public function success(string $no_reg)
    {
        $pendaftaran = SpmbPendaftaran::where('no_reg', $no_reg)->firstOrFail();
        return Inertia::render('Spmb/Success', [
            'no_reg' => $no_reg,
            'nama' => $pendaftaran->siswa->nama_lengkap
        ]);
    }
}
