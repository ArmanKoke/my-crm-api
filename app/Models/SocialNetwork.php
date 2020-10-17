<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialNetwork extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}
