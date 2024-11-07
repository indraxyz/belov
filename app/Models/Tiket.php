<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tiket';
    protected $fillable = [
        'no_tiket', 'status_peserta', 'pemohon', 'perusahaan', 'pic_hrd', 'nik', 'nama_lengkpa', 'no_kartu_peserta', 'tgl_lahir', 'nohp', 'data_perbaikan', 'email', 'file_kartu_bpjs', 'file_ktp', 'file_foto', 'file_formulir', 'status_tiket', 'id_admin'
    ];
    // prepare json / array serialize
    protected $casts = [
        'status_peserta' => 'int',
        'pemohon' => 'int',
        'status_tiket' => 'int',
        'id_admin' => 'int',
   ];

    // custom field
    public function getCreatedAtAttribute($time)
    {
        return Carbon::parse($time)->format('d-m-Y H:i');
    }
    public function getTglLahirAttribute($time)
    {
        return Carbon::parse($time)->format('d-m-Y');
    }

    // relasi
    public function akun()
    {
        return $this->belongsTo('App\Models\Akun', 'id_admin');
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
