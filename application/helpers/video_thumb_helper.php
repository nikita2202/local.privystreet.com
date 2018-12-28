<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH.'composer/vendor/autoload.php';



if (! function_exists("create_video_thumb")) {
    function create_video_thumb($url)
    {
        $ffprobe = FFMpeg\FFProbe::create();

        $duration = $ffprobe
        ->format($url['0']) // extracts file informations
        ->get('duration');


        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($url['0']);
        $thumbUrl = getcwd() . "/public/thumbnails/".time() . '.jpg';
        $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($duration/3))
        ->save($thumbUrl);

        return $thumbUrl;
    }
}
