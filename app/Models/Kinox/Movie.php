<?php namespace App\Models\Kinox;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'kinox_movies';

    protected $fillable = ['id', 'name', 'language'];

    public $incrementing = false;

    public function updateDue()
    {
        $neverUpdated = $this->created_at == $this->updated_at;
        $updateOld = (new DateTime())->diff(new DateTime($this->updated_at))->format('%d') >= 1;

        return $neverUpdated || $updateOld;
    }
}
