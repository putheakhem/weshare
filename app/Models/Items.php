<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FileDetails;
use App\Models\User;

class Items extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $guarded = [];
    public function file_details()
    {
        return $this->belongsTo(FileDetails::class,'file_de_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
