<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;

class Tiket_progres extends Model
{
    use HasFactory;

    protected $table = 'tiket_progres';
    protected $fillable = [
        'progres', 'catatan', 'id_tiket'
    ];
    // prepare json array serialize
    protected $casts = [
            'id_tiket' => 'int',
            'progres' => 'int',
    ];

    // relasi
    public function tiket()
    {
        return $this->belongsTo('App\Models\Tiket', 'id_tiket');
    }

    public function getCreatedAtAttribute($time)
    {
        return Carbon::parse($time)->format('d-m-Y H:i');
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
