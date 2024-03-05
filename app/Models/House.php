<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function family_card()
    {
        return $this->hasMany(FamilyCard::class);
    }
}
