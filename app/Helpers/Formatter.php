<?php

namespace App\Helpers;

use Carbon\Carbon;

class Formatter
{
    /**
     * Format nomor telepon ke format yang diinginkan
     *
     * @param  string  $phone  Nomor telepon yang akan diformat
     */
    public static function phone(string $phone): string
    {
        // Hilangkan karakter selain angka dan '+'
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Hapus awalan '0' jika ada
        if (substr($phone, 0, 1) == '0') {
            $phone = substr($phone, 1);
        }

        // Hapus prefiks '+62' jika ada
        if (substr($phone, 0, 3) == '+62') {
            $phone = substr($phone, 3);
        }

        // Pastikan nomor telepon dimulai dengan '62'
        if (substr($phone, 0, 2) != '62') {
            $phone = '62'.$phone;
        }

        return $phone; // Kembalikan nomor telepon yang telah diformat
    }

    /**
     * Format tanggal
     *
     * @param  string|null  $date  Tanggal yang akan diformat
     * @param  string  $format  Format tanggal yang diinginkan
     * @param  string|null  $returnIfError  Nilai yang akan dikembalikan jika terjadi error (default: null)
     */
    public static function date(?string $date, string $format = 'd F Y', ?string $returnIfError = null): ?string
    {
        try {
            return Carbon::parse($date)->translatedFormat($format);
        } catch (\Throwable) {
            return $returnIfError ?? null;
        }
    }

    /**
     * Format tanggal dan waktu
     *
     * @param  string|null  $date  Tanggal yang akan diformat
     * @param  string  $format  Format tanggal yang diinginkan
     * @param  string|null  $returnIfError  Nilai yang akan dikembalikan jika terjadi error (default: null)
     */
    public static function datetime(?string $date, string $format = 'd F Y H:i', ?string $returnIfError = null): ?string
    {
        try {
            return Carbon::parse($date)->translatedFormat($format);
        } catch (\Throwable) {
            return $returnIfError ?? null;
        }
    }

    /**
     * Format mata uang
     *
     * @param  string|int  $amount  Jumlah uang yang akan diformat
     */
    public static function currency(string|int $amount): string
    {
        return formatCurrency($amount);
    }
}
