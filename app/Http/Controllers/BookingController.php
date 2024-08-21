<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class BookingController extends Controller
{
    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'kota' => 'required|string|max:255',
                'usia' => 'required|integer|min:1',
            ]);
    
            // Simpan booking
            Booking::create([
                'nama' => $request->input('nama'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'kota' => $request->input('kota'),
                'usia' => $request->input('usia'),
            ]);
    
            return redirect()->route('bookings.create')->with('success', 'Booking added successfully');
        } catch (ValidationException $e) {
            // Penanganan error validasi
            Log::error('Validation Exception: ' . $e->getMessage());
            return redirect()->route('bookings.create')
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validation failed');
        } catch (HttpException $e) {
            // Penanganan HTTP exceptions seperti 405
            Log::error('HTTP Exception: ' . $e->getMessage());
            return redirect()->route('bookings.create')->with('error', 'HTTP Error: ' . $e->getMessage());
        } catch (Exception $e) {
            // Penanganan error umum
            Log::error('Failed to create booking: ' . $e->getMessage());
            return redirect()->route('bookings.create')->with('error', 'Internal Server Error. Please try again later.');
        }
    }
}
