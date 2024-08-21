<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akademik\TahunAjaran;
use App\Models\Camaba\FormulirF02\FormulirMatakuliahCpm;
use App\Models\Cms\Timeline;
use App\Models\Master\Provinsi;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\Register;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function admin()
    {
        return view('pages.admin.dashboard');
    }

    public function animoDaerah()
    {
        $categories = $this->getProvinsiStats()['provinsiNames'];
        $totals = $this->getProvinsiStats()['totals'];

        return response()->json(['categories' => $categories, 'totals' => $totals]);
    }

    public function approvDaerah()
    {
        $categoriesApproved = $this->getProvinsiApprovedStats()['provinsiNames_approved'];
        $totalsApproved = $this->getProvinsiApprovedStats()['totals_approved'];

        return response()->json(['categories' => $categoriesApproved, 'totals' => $totalsApproved]);
    }

    public function approvLulus()
    {
        $namaMKs = $this->getMatakuliahStats()['namaMKs'];
        $jumlahs = $this->getMatakuliahStats()['jumlahs'];

        return response()->json(['categories' => $namaMKs, 'totals' => $jumlahs]);
    }

    private function getRegistersCount()
    {
        return Register::count();
    }

    private function getApprovedFormulirCount()
    {
        return Formulir::where('status_administrasi', 'APPROVED')->count();
    }

    private function getAcceptedFormulirCount()
    {
        return Formulir::where('status_kelulusan', 'LULUS')->count();
    }

    private function getPendaftarStats()
    {
        // Pendatar
        $total_pendaftar = Formulir::count();
        $persentase_pendaftar_lakilaki = (int) ($total_pendaftar > 0 ? (Formulir::where('jenis_kelamin', 'L')->count() / $total_pendaftar) * 100 : 0);
        $persentase_pendaftar_perempuan = (int) ($total_pendaftar > 0 ? (Formulir::where('jenis_kelamin', 'P')->count() / $total_pendaftar) * 100 : 0);

        return compact('total_pendaftar', 'persentase_pendaftar_lakilaki', 'persentase_pendaftar_perempuan');
    }

    private function getPendaftarApprovedStats()
    {
        $total_pendaftar_approved = Formulir::where('status_administrasi', 'APPROVED')->count();
        $persentase_pendaftar_lakilaki_approved = (int) ($total_pendaftar_approved > 0 ? (Formulir::where('jenis_kelamin', 'L')->where('status_administrasi', 'APPROVED')->count() / $total_pendaftar_approved) * 100 : 0);
        $persentase_pendaftar_perempuan_approved = (int) ($total_pendaftar_approved > 0 ? (Formulir::where('jenis_kelamin', 'P')->where('status_administrasi', 'APPROVED')->count() / $total_pendaftar_approved) * 100 : 0);

        return compact('total_pendaftar_approved', 'persentase_pendaftar_lakilaki_approved', 'persentase_pendaftar_perempuan_approved');
    }

    private function getProvinsiStats()
    {
        $provinsis = Provinsi::withCount('register')->get();

        $data_pendaftar = $provinsis->map(function ($provinsi) {
            return [
                'provinsi_id' => $provinsi->id,
                'provinsi_name' => $provinsi->nama,
                'total' => $provinsi->register_count,
            ];
        })->toArray();

        $provinsiNames = array_column($data_pendaftar, 'provinsi_name');
        $totals = array_column($data_pendaftar, 'total');

        return compact('provinsiNames', 'totals');
    }

    private function getProvinsiApprovedStats()
    {
        $data_pendaftar_approved = Provinsi::withCount(['register' => function ($query) {
            $query->whereHas('formulir', function ($query) {
                $query->where('status_administrasi', 'APPROVED');
            });
        }])->get()->map(function ($provinsi) {
            return [
                'provinsi_id_approved' => $provinsi->id,
                'provinsi_name_approved' => $provinsi->nama,
                'total_approved' => $provinsi->register_count,
            ];
        });

        $provinsiNames_approved = array_column($data_pendaftar_approved->toArray(), 'provinsi_name_approved');
        $totals_approved = array_column($data_pendaftar_approved->toArray(), 'total_approved');

        return compact('provinsiNames_approved', 'totals_approved');
    }

    private function getMatakuliahStats()
    {
        // Peserta Lolos Berkas Per Matakuliah yang dibuka
        $result = [];
        $formulirs = Formulir::where('status_administrasi', 'APPROVED')->get();

        foreach ($formulirs as $formulir) {
            $matkulIds = [];
            $formulirMatakuliahCpms = FormulirMatakuliahCpm::where('formulir_id', $formulir->id)->get();

            foreach ($formulirMatakuliahCpms as $formulirMatakuliahCpm) {
                if (! in_array($formulirMatakuliahCpm->matkul_id, $matkulIds)) {
                    if (! isset($result[$formulirMatakuliahCpm->matkul_id])) {
                        $result[$formulirMatakuliahCpm->matkul_id] = 0;
                    }
                    $result[$formulirMatakuliahCpm->matkul_id]++;
                    $matkulIds[] = $formulirMatakuliahCpm->matkul_id;
                }
            }
        }

        $matakuliahData = [];

        foreach ($result as $matkulId => $jumlah) {
            $matakuliah = Matakuliah::find($matkulId);

            $matakuliahData[] = [
                'nama_mk' => $matakuliah ? $matakuliah->nama_mk : null,
                'jumlah' => $jumlah,
            ];
        }

        $namaMKs = array_column($matakuliahData, 'nama_mk');
        $jumlahs = array_column($matakuliahData, 'jumlah');

        return ['namaMKs' => $namaMKs, 'jumlahs' => $jumlahs];
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->translatedFormat('j F Y') : '-';
    }

    private function isClosed($endDate)
    {
        $today = Carbon::today();

        return $endDate ? Carbon::parse($endDate)->lessThan($today) : false;
    }

    private function getTimelineInfo()
    {
        $timelines = Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->first();

        $defaultDate = '-';

        $tanggal_mulai_pendaftaran = $this->formatDate($timelines->tanggal_mulai_pendaftaran ?? null);
        $tanggal_selesai_pendaftaran = $this->formatDate($timelines->tanggal_selesai_pendaftaran ?? null);
        $tanggal_mulai_administrasi = $this->formatDate($timelines->tanggal_mulai_administrasi ?? null);
        $tanggal_selesai_administrasi = $this->formatDate($timelines->tanggal_selesai_administrasi ?? null);
        $tanggal_mulai_assesmen = $this->formatDate($timelines->tanggal_mulai_assesmen ?? null);
        $tanggal_seleksi_evaluasi_diri = $this->formatDate($timelines->tanggal_seleksi_evaluasi_diri ?? null);

        $is_pendaftaran_closed = $this->isClosed($timelines->tanggal_selesai_pendaftaran ?? null);
        $is_administrasi_closed = $this->isClosed($timelines->tanggal_selesai_administrasi ?? null);
        $is_assesmen_closed = $this->isClosed($timelines->tanggal_mulai_assesmen ?? null);
        $is_evaluasi_diri_closed = $this->isClosed($timelines->tanggal_seleksi_evaluasi_diri ?? null);

        return compact(
            'tanggal_mulai_pendaftaran',
            'tanggal_selesai_pendaftaran',
            'tanggal_mulai_administrasi',
            'tanggal_selesai_administrasi',
            'tanggal_mulai_assesmen',
            'tanggal_seleksi_evaluasi_diri',
            'is_pendaftaran_closed',
            'is_administrasi_closed',
            'is_assesmen_closed',
            'is_evaluasi_diri_closed'
        );
    }

    private function getTotalPayments()
    {
        return Pembayaran::where('status', 'lunas')->count();
    }
}
