<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'category',
        'date',
        'user_id', // <-- Add this line
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
