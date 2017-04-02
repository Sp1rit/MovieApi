<?php namespace App\Models\BurningSeries;

use Illuminate\Database\Eloquent\Model;

class Mirror extends Model
{
    protected $table = 'bs_mirrors';

    protected $fillable = ['media_id', 'season', 'episode', 'hoster', 'mirror_id'];

    public function scopeGetBy($query, $id, $season, $episode)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode])->get();
    }

    public function scopeFirstBy($query, $id, $season, $episode, $hoster)
    {
        return $query->where(['media_id' => $id, 'season' => $season, 'episode' => $episode, 'hoster' => $hoster])->firstOrfail();
    }
}
