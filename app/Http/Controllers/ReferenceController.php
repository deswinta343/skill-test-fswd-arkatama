<?php

namespace App\Http\Controllers;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Master\JenjangPendidikan;
use App\Models\Master\KabupatenKota;
use App\Models\Master\KategoriBerita;
use App\Models\Master\Kecamatan;
use App\Models\Master\Provinsi;
use App\Models\Payment\PaymentChannel;
use App\Models\Rpl\Asesor;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use ResponseFormatter;

class ReferenceController extends Controller
{
    private function formatResults(Collection|array $data, string $key = 'id', string $value = 'name', int $limit = 10, ?callable $callbackLabel = null)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item[$key],
                'text' => $callbackLabel ? $callbackLabel($item) : $item[$value],
            ];
        }

        return collect($result)->slice(0, $limit)->values();
    }

    public function prodi(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama_prodi';

        $query = ProgramStudi::where($key, 'like', "%{$search}%");
        if (auth()->user()->getRoleNames()->first() == 'admin-fakultas') {
            $query->whereHas('fakultas', function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        if ($request->query('in_prodi_pilihan')) {
            $query->whereHas('prodi_pilihan', function ($query) {
                $query->where('is_active', '1');
            });
        }
        $results = $query->limit($limit)->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function user(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'name';
        $results = User::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function mataKuliah(Request $request)
    {
        $prodi = $request->get('prodi');
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama_mk';

        $results = Matakuliah::where($key, 'like', "%{$search}%");

        if ($prodi) {
            $results = $results->where('prodi_id', $prodi);
        }

        if (auth()->user()->getRoleNames()->first() == 'admin-prodi') {
            $results = $results->whereHas('prodi', function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        $results = $results->limit($limit)->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function asesor(Request $request)
    {
        $search = $request->get('q');
        $limit = $request->get('limit', 10);
        $key = 'nama_lengkap';
        $query = Asesor::where($key, 'like', "%{$search}%");

        if (auth()->user()->getRoleNames()->first() == 'admin-prodi') {
            $query = $query->whereHas('prodi', function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        if ($request->query('jenis_asesor')) {
            $query = $query->where('jenis_asesor', $request->query('jenis_asesor'));
        }

        $results = $query->limit($limit)->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function provinsi(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama';
        $results = Provinsi::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function kab_kota(Request $request)
    {
        $provinsi_id = $request->get('provinsi');
        $search = $request->get('q');
        $limit = 20;
        $key = 'nama';
        $results = KabupatenKota::where($key, 'like', "%{$search}%");
        if ($provinsi_id) {
            $results = $results->where('provinsi_id', $provinsi_id);
        }

        $results = $results->limit($limit)->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function tahun_ajaran(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'tahun_ajaran';
        $results = TahunAjaran::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function kecamatan(Request $request)
    {
        $kab_kota_id = $request->get('kota');
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama';
        $results = Kecamatan::where($key, 'like', "%{$search}%");

        if ($kab_kota_id) {
            $results = $results->where('kabupaten_kota_id', $kab_kota_id);
        }
        $results = $results->limit($limit)->get();
        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function icon(Request $request)
    {
        $iconFile = file_get_contents(public_path('assets/libs/fontawesome/css/all.css'));
        preg_match_all("/\.fa-.*:before/", $iconFile, $matches);
        $result = [];

        foreach ($matches[0] as $match) {
            $name = str_replace([':before', '.'], '', $match);
            $result[] = [
                'id' => "fal $name",
                'name' => "fal $name",
            ];
        }

        if ($request->term) {
            $result = collect($result)->filter(function ($value, $key) use ($request) {
                return stripos($value['name'], $request->term);
            })->values()->toArray();
        }

        // $result = $this->generateReference($result);
        $result = $this->formatResults($result);

        return ResponseFormatter::success('success get icons', $result);
    }

    public function status_administrasi_filter(Request $request)
    {
        $status_administrasi = [
            ['id' => 'SUBMITTED', 'name' => 'Submitted - Menunggu Verifikasi'],
            ['id' => 'PROPOSED', 'name' => 'Proposed - Menunggu Persetujuan'],
            ['id' => 'REVISED', 'name' => 'Revised - Menunggu Revisi'],
            ['id' => 'REJECTED', 'name' => 'Rejected - Ditolak'],
            ['id' => 'APPROVED', 'name' => 'Approved - Disetujui'],
        ];

        $search = $request->get('q');
        $limit = 10;
        $results = collect($status_administrasi);
        if ($search) {
            $results = $results->filter(function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }
        $results = $results->slice(0, $limit)->values()->toArray();
        $results = $this->formatResults($results);

        return ResponseFormatter::success('success', $results);
    }

    public function status_administrasi_verify(Request $request, $formulir_id)
    {
        $menunggu = FormulirBerkasPersyaratan::where('formulir_id', $formulir_id)
            ->where('is_valid', '2')
            ->get();

        $tidak_valid = FormulirBerkasPersyaratan::where('formulir_id', $formulir_id)
            ->where('is_valid', '0')
            ->get();

        if ($menunggu->count() == 0) {
            if ($tidak_valid->count() == 0) {
                $status_administrasi = [
                    ['id' => 'REJECTED', 'name' => 'Rejected - Ditolak'],
                    ['id' => 'APPROVED', 'name' => 'Approved - Disetujui'],
                ];
            } else {
                $status_administrasi = [
                    ['id' => 'REVISED', 'name' => 'Revised - Menunggu Revisi'],
                ];
            }
        } else {
            $status_administrasi = [
                ['id' => 'REJECTED', 'name' => 'Rejected - Ditolak'],
            ];
        }

        $search = $request->get('q');
        $limit = $menunggu->count() == 0 ? ($tidak_valid->count() == 0 ? 2 : 1) : 1;
        $results = collect($status_administrasi);
        if ($search) {
            $results = $results->filter(function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }
        $results = $results->slice(0, $limit)->values()->toArray();
        $results = $this->formatResults($results);

        return ResponseFormatter::success('success', $results);
    }

    public function status_kelulusan(Request $request)
    {
        $status_kelulusan = [
            ['id' => 'LULUS', 'name' => 'Lulus'],
            ['id' => 'TIDAK_LULUS', 'name' => 'Tidak Lulus'],
        ];

        $search = $request->get('q');
        $limit = 10;
        $results = collect($status_kelulusan);
        if ($search) {
            $results = $results->filter(function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }
        $results = $results->slice(0, $limit)->values()->toArray();
        $results = $this->formatResults($results);

        return ResponseFormatter::success('success', $results);
    }

    public function jenjang_pendidikan(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama';
        $results = JenjangPendidikan::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function kategori_berita(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'name';
        $results = KategoriBerita::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit);

        return ResponseFormatter::success('success', $results);
    }

    public function formulir(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'nama_lengkap';
        $results = Formulir::with('tahun_ajaran')
            ->where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        $results = $this->formatResults($results, 'id', $key, $limit, function ($item) {
            return $item['nama_lengkap'].' - '.$item['tahun_ajaran']['tahun_ajaran'];
        });

        return ResponseFormatter::success('success', $results);
    }

    public function matakuliah_asesor(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'asesor.nama_lengkap';
        $matakuliah_id = $request->get('matakuliah_id');
        $results = MatakuliahAsesor::leftJoin('matakuliah', 'matakuliah_asesor.matkul_id', '=', 'matakuliah.id')
            ->leftJoin('asesor', 'matakuliah_asesor.asesor_id', '=', 'asesor.id')
            ->where($key, 'like', "%{$search}%")
            ->where('matkul_id', $matakuliah_id)
            ->limit($limit)
            ->select('matakuliah_asesor.*', 'matakuliah.nama_mk', 'asesor.nama_lengkap')
            ->get();

        $results = $this->formatResults($results, 'id', 'nama_lengkap', $limit, $matakuliah_id ? null : function ($item) {
            return $item['nama_lengkap'].' - '.$item['nama_mk'];
        });

        return ResponseFormatter::success('success', $results);
    }

    public function matakuliah_asesor_filter(Request $request, $jenis)
    {
        $limit = request()->get('limit', 10);
        $matakuliah_id = $request->get('matakuliah_id');
        $results = MatakuliahAsesor::leftJoin('matakuliah', 'matakuliah_asesor.matkul_id', '=', 'matakuliah.id')
            ->leftJoin('asesor', 'matakuliah_asesor.asesor_id', '=', 'asesor.id')
            ->where('asesor.jenis_asesor', $jenis)
            ->where('matkul_id', $matakuliah_id)
            ->limit($limit)
            ->select('matakuliah_asesor.*', 'matakuliah.nama_mk', 'asesor.nama_lengkap as nama_lengkap')
            ->get();

        $results = $this->formatResults($results, 'id', 'nama_lengkap', $limit, $matakuliah_id ? null : function ($item) {
            return $item['nama_lengkap'].' - '.$item['nama_mk'];
        });
        if ($request->query('required_null')) {
            $results->prepend(['id' => '-1', 'text' => 'Kosongkan Asesor '.ucfirst($jenis)]);
        }

        return ResponseFormatter::success('success', $results);
    }

    public function channel(Request $request)
    {
        $search = $request->get('q');
        $limit = 10;
        $key = 'name';
        $results = PaymentChannel::where($key, 'like', "%{$search}%")
            ->limit($limit)
            ->get();
        $results = $this->formatResults($results, 'id', $key, $limit);
        $results->prepend(['id' => '1', 'text' => 'COD']);

        return ResponseFormatter::success('success', $results);
    }
}
