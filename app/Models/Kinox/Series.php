<?php namespace App\Models\Kinox;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'kinox_series';

    protected $fillable = ['id', 'name', 'language'];

    public $incrementing = false;

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'series_id')->ordered();
    }

    public function updateDue()
    {
        $neverUpdated = $this->created_at == $this->updated_at;
        $updateOld = (new DateTime())->diff(new DateTime($this->updated_at))->format('%d') >= 1;

        return $neverUpdated || $updateOld;
    }
}