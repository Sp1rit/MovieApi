<?php namespace App\Events;

use App\Events\Event;
use App\Http\Controllers\BurningSeriesController;
use App\Http\Controllers\KinoxController;
use App\Models\BurningSeries\Episode as BsEpisode;
use App\Models\BurningSeries\Series as BsSeries;
use App\Models\Kinox\Episode as KinoxEpisode;
use App\Models\Kinox\Media as KinoxMedia;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewEpisodeEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $provider;
    public $series_id;
    public $series_name;
    public $season;
    public $episode;
    public $language;

    public function __construct()
    {
    }

    public static function Kinox(KinoxMedia $series, KinoxEpisode $episode)
    {
        $event = new NewEpisodeEvent();
        $event->provider = KinoxController::PROVIDER;
        $event->series_id = $series->id;
        $event->series_name = $series->name;
        $event->season = $episode->season;
        $event->episode = $episode->episode;
        $event->language = -1;

        return $event;
    }

    public static function Bs(BsSeries $series, BsEpisode $episode)
    {
        $event = new NewEpisodeEvent();
        $event->provider = BurningSeriesController::PROVIDER;
        $event->series_id = $series->id;
        $event->series_name = $series->name;
        $event->season = $episode->season;
        $event->episode = $episode->episode;
        $event->language = $episode->german = '' ? 'en' : 'de';

        return $event;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
