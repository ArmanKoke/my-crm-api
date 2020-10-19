<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string company_name
 * @property string description
 * @property string notes
 * @property int status_id
 * @property int manager_id
 */
class Deal extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class);
    }
}
