<?php

use App\Models\Rpl\ProdiPilihan;
use App\Models\Setting\SystemSettingModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

if (! function_exists('getSetting')) {

    function getSetting(string $key, mixed $defaultValue = null): string
    {
        $setting = SystemSettingModel::where([
            'is_active' => '1',
            'name' => $key,
        ])->first();
        if ($setting) {
            return $setting->value;
        } else {
            return $defaultValue;
        }
    }
}

if (! function_exists('c_option')) {
    /**
     * c_options
     * fungsi untuk mengconvert collection ke array untuk custom select
     */
    function c_option(Collection|Model $data, ?string $labelCol = 'name', string $valCol = 'id'): array
    {
        if ($data instanceof Model) {
            $d = collect([$data])->pluck($labelCol, $valCol)->all();

            return $d;
        }
        $d = $data->pluck($labelCol, $valCol)->all();

        return $d;
    }
}

function humanFileSize(int $size, $precision = 1)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }

    return round($size, $precision).' '.$units[$i];
}

function makeEllipsis(string $str, int $maxLength): string
{
    return substr($str, 0, $maxLength).'...';
}

if (! function_exists('getFileInfo')) {
    function getFileInfo(string $path, string $disk = 'public'): array
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return [];
        }
        $fileInfo = pathinfo($storage->path($path));
        $size = filesize($storage->path($path));
        $res['preview'] = Storage::url($path);
        $res['path'] = $path;
        $res['size'] = humanFileSize($size);
        $res['filename'] = makeEllipsis($fileInfo['filename'], 20).'.'.$fileInfo['extension'];

        return $res;
    }
}

if (! function_exists('str_elipsis')) {
    function str_elipsis($string, $length = 100, $append = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        } else {
            return substr($string, 0, $length).$append;
        }
    }
}

if (! function_exists('formatDateFromDatabase')) {
    /**
     * Format date time
     *
     * @param  string|null  $date  Original date from database
     * @param  string  $toFormat  Format to be displayed (default: 'd F Y H:i')
     * @param  bool  $diff  Show difference from now (default: false)
     */
    function formatDateFromDatabase(?string $date, string $toFormat = 'd F Y H:i', bool $diff = true): ?array
    {
        if ($date == null) {
            return null;
        }
        $formattedDate = Carbon::parse($date)->locale('id')->translatedFormat($toFormat);
        $diffDate = $diff ? Carbon::parse($date)->diffForHumans() : '';

        return [
            'formatted' => $formattedDate,
            'diff' => $diffDate,
        ];
    }
}

if (! function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        $formatted_amount = number_format($amount, 2, ',', '.');

        return 'Rp. '.$formatted_amount;
    }
}

if (! function_exists('checkJadwalPendaftaran')) {
    function checkJadwalPendaftaran()
    {
        $register = auth()->user()->register;
        if (! $register) {
            return false;
        }

        $prodiPilihan = ProdiPilihan::where([
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'prodi_id' => $register->prodi_id,
        ])->first();

        if (! $prodiPilihan) {
            return false;
        }

        $tanggalMulai = Carbon::parse($prodiPilihan->tanggal_mulai_pendaftaran);
        $tanggalSelesai = Carbon::parse($prodiPilihan->tanggal_selesai_administrasi);

        return Carbon::now()->between($tanggalMulai, $tanggalSelesai);
    }
}

if (! function_exists('checkIfPembayaranLunas')) {
    function checkIfPembayaranLunas()
    {
        return auth()->user()->register->pembayaran?->status == 'lunas';
    }
}

if (! function_exists('directRedirect')) {
    /**
     * Directly redirect to a route with a message
     * without needing to return a response from controller
     *
     * @param  string  $route  Route name
     * @param  string  $key  Session key
     * @param  string  $message  Message
     * @return void
     */
    function directRedirect($route, $key, $message)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->redirectToRoute($route)->with($key, $message)
        );
    }
}

if (! function_exists('directJsonResponse')) {
    /**
     * Directly redirect to a route with a message
     * without needing to return a response from controller
     */
    function directJsonResponse(\Illuminate\Http\JsonResponse $response): void
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            $response
        );
    }
}

if (! function_exists('redirectIfOutsideOfSchedule')) {
    /**
     * Redirect user if outside of registration schedule
     */
    function redirectIfOutsideOfSchedule(): void
    {
        $message = 'Mohon maaf, pendaftaran telah ditutup. Silakan hubungi panitia PMB untuk bantuan lebih lanjut';
        if (! checkJadwalPendaftaran()) {
            if (request()->wantsJson()) {
                directJsonResponse(
                    ResponseFormatter::error($message)
                );
            } else {
                directRedirect('dashboard', 'error', $message);
            }
        }
    }
}

if (! function_exists('redirectIfFileIsNotFulfilled')) {
    /**
     * Redirect user if file is not fulfilled
     *
     * @param  bool  $checkScheduleToo  Check schedule too
     */
    function redirectIfFileIsNotFulfilled(bool $checkScheduleToo = true): void
    {
        if ($checkScheduleToo) {
            redirectIfOutsideOfSchedule();
        }

        $message = 'Mohon untuk melengkapi berkas persyaratan terlebih dahulu';
        if (! auth()->user()->register->isBerkasLengkap()) {
            if (request()->wantsJson()) {
                directJsonResponse(
                    ResponseFormatter::error($message)
                );
            } else {
                directRedirect('dashboard', 'error', $message);
            }
        }
    }
}

if (! function_exists('redirectIfFormAIsNotFulfilled')) {
    /**
     * Redirect user if form A is not fulfilled
     *
     * @param  bool  $checkScheduleToo  Check schedule too
     */
    function redirectIfFormAIsNotFulfilled(bool $checkScheduleToo = true): void
    {
        if ($checkScheduleToo) {
            redirectIfOutsideOfSchedule();
        }

        $message = 'Mohon untuk melengkapi formulir tipe A terlebih dahulu';
        if (! auth()->user()->register->cekFormulirALengkap()) {
            if (request()->wantsJson()) {
                directJsonResponse(
                    ResponseFormatter::error($message)
                );
            } else {
                directRedirect('dashboard', 'error', $message);
            }
        }
    }
}

function generateStatus($detail_penilaian, $column, $labelA, $labelB)
{
    $accumulatedVal = '0';
    if ($detail_penilaian && count($detail_penilaian) > 1) {
        if ($detail_penilaian[0]->{$column} == $detail_penilaian[0]->{$column}) {
            $accumulatedVal = $detail_penilaian[0]->{$column};
        } elseif ($detail_penilaian[0]->{$column} == '2' || $detail_penilaian[1]->{$column} == '2') {
            $accumulatedVal = '2';
        } elseif ($detail_penilaian[0]->{$column} == '0' || $detail_penilaian[1]->{$column} == '0') {
            $accumulatedVal = '0';
        }
    } elseif ($detail_penilaian && count($detail_penilaian) == 1) {
        $accumulatedVal = $detail_penilaian[0]->{$column};
    } else {
        return 'Belum Dinilai';
    }

    switch ($accumulatedVal) {
        case '1':
            return $labelA;
            break;
        case '2':
            return 'Menunggu Validasi';
            break;
        default:
            return $labelB;
            break;
    }
}
