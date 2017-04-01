<?php namespace App\Models\Kinox;

use Illuminate\Database\Eloquent\Model;

class Mirror extends Model
{
    protected $table = 'kinox_mirrors';

    protected $fillable = ['media_id', 'season', 'episode', 'hoster', 'hoster_id', 'count'];

    public function scopeGetBy($query, $id, $season, $episode)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode])->get();
    }

    public function scopeFirstBy($query, $id, $season, $episode, $hoster)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode, 'hoster_id' => $hoster])->firstOrfail();
    }
}
