<?php namespace App\Models\Movie4k;

use Illuminate\Database\Eloquent\Model;

class Mirror extends Model
{
    protected $table = 'movie4k_mirrors';

    protected $fillable = ['media_id', 'season', 'episode', 'hoster', 'hoster_id', 'quality'];

    public function scopeGetBy($query, $id, $season, $episode)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode])->orderBy('quality', 'desc')->groupBy('hoster')->get();
    }

    public function scopeFirstBy($query, $id, $season, $episode, $hoster)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode, 'hoster' => $hoster])->firstOrfail();
    }
}
