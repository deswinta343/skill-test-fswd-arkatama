<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'jenis_kelamin',
        'kota',
        'usia',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->kode = 'BOOK-' . strtoupper(uniqid());
        });
    }
}
