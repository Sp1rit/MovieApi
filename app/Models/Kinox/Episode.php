<?php namespace App\Models\Kinox;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $table = 'kinox_episodes';

    protected $fillable = ['series_id', 'season', 'episode'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('series_id')->orderBy('season')->orderBy('episode');
    }
}
