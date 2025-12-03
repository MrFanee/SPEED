<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\User;

class UploadFailure extends Model
{
    protected $table = 'upload_failures';

    protected $fillable = [
        'module',
        'raw_data',
        'error_message',
        'status',
        'uploaded_by',
    ];

    /**
     * Raw data otomatis di-decode JSON jadi array.
     */
    protected $casts = [
        'raw_data' => 'array',
    ];

    /**
     * Relasi ke user yang upload.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
