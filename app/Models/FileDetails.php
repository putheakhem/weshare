<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Types;
use App\Models\Majors;

class FileDetails extends Model
{
    use HasFactory;

    protected $table = 'file_details';
    protected $fillable = [
        'major_id',
        'filetype_id',
        'title',
        'desc'
    ];
    protected $guarded = [];

    public function majors()
    {
        return $this->belongsTo(Majors::class, 'major_id');
    }

    public function filetype()
    {
        return $this->belongsTo(Types::class, 'filetype_id');
    }
}
