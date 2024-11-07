<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Detail_akun extends Model
{
    use HasFactory;

    protected $table = 'detail_akun';
    protected $fillable = [
        'nama_lengkap', 'email', 'phone', 'id_akun'
    ];
    // prepare json array serialize
    protected $casts = [
        'id_akun' => 'int',
   ];

    // relasi
    public function akun()
    {
        return $this->belongsTo('App\Models\Akun', 'id_akun');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
