<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'stunting_indicators';

    protected $fillable = [
        'kab/kota', 'latitude', 'longitude', 'stunting', 'hamil', 'tambah_darah', 
        'persalinan_nakes', 'vit_a', 'bblr', 'imd', 'asi_baduta', 'lama_asi', 
        'asi_pendamping', 'imunisasi', 'vit_a_6_11', 'vit_a_12_59', 'vit_a_6_59', 
        'cakupan', 'jiwa_rt', 'sanitasi', 'air_minum', 'ipm', 'miskin', 'lisa_cluster'
    ];
}
