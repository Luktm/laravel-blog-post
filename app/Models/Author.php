<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class Author extends Model
{
    use HasFactory;

    // Relation defined for foreign key see Profile.php
    public function profile() {
        return $this->hasOne(Profile::class);
    }
}
