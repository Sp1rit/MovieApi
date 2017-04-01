<?php namespace App\Models\BurningSeries;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $table = 'bs_episodes';

    protected $fillable = ['series_id', 'season', 'episode', 'german', 'english'];

    public function name()
    {
        return $this->german == '' ? $this->english : $this->german;
    }

    public function language()
    {
        return $this->german == '' ? 'en' : 'de';
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('series_id')->orderBy('season')->orderBy('episode');
    }
}
