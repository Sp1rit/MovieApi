<?php namespace App\Models\BurningSeries;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'bs_series';

    protected $fillable = ['id', 'name', 'end'];

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'series_id')->ordered();
    }

    public static function search($name)
    {
        return Series::whereRaw( "MATCH(name) AGAINST(? IN BOOLEAN MODE) OR name = ?", [trim($name), trim($name)])->get();
    }

    public function updateDue()
    {
        $neverUpdated = $this->created_at == $this->updated_at;
        $updateOld = (new DateTime())->diff(new DateTime($this->updated_at))->format('%d') >= 1;

        return $neverUpdated || $updateOld;
    }
}
