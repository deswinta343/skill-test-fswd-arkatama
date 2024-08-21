<?php

namespace App\Traits;

trait Editable
{
    protected static function bootEditable()
    {
        static::creating(function ($model) {
            if (getRole() == 'camaba') {
                if (isset(auth()->user()->register->formulir)) {
                    if (! (auth()->user()->register?->formulir?->status_administrasi == 'SUBMITTED' || auth()->user()->register?->formulir->status_administrasi == 'REVISED') && auth()->user()->register?->formulir->status_kelulusan == null) {
                        directJsonResponse(\ResponseFormatter::error('Anda tidak dapat menambahkan bagian ini'));
                    }
                }
            }
        });
        static::updating(function ($model) {
            if (getRole() == 'camaba') {
                if (isset(auth()->user()->register->formulir)) {
                    if (! (auth()->user()->register?->formulir?->status_administrasi == 'SUBMITTED' || auth()->user()->register?->formulir->status_administrasi == 'REVISED') && auth()->user()->register?->formulir->status_kelulusan == null) {
                        directJsonResponse(\ResponseFormatter::error('Anda tidak dapat mengubah bagian ini'));
                    }
                }
            }
        });
        static::saving(function ($model) {
            if (getRole() == 'camaba') {
                if (isset(auth()->user()->register->formulir)) {
                    if (! (auth()->user()->register?->formulir?->status_administrasi == 'SUBMITTED' || auth()->user()->register?->formulir->status_administrasi == 'REVISED') && auth()->user()->register?->formulir->status_kelulusan == null) {
                        directJsonResponse(\ResponseFormatter::error('Anda tidak dapat mengubah bagian ini'));
                    }
                }
            }
        });
        static::deleting(function ($model) {
            if (getRole() == 'camaba') {
                if (isset(auth()->user()->register->formulir)) {
                    if (! (auth()->user()->register?->formulir?->status_administrasi == 'SUBMITTED' || auth()->user()->register?->formulir->status_administrasi == 'REVISED') && auth()->user()->register?->formulir->status_kelulusan == null) {
                        directJsonResponse(\ResponseFormatter::error('Anda tidak dapat menghapus bagian ini'));
                    }
                }
            }
        });
    }
}
