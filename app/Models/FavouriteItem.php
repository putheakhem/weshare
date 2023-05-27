<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Items;
use App\Models\User;
class FavouriteItem extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'item_id'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Item model
    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}
