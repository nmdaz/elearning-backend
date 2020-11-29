<?php

namespace App\Includes;

class YoutubeIdExtractor {
	public function extractId($url) 
	{
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

		if (count($match) === 0) return null;

		$youtubeId = $match[1];
		return $youtubeId;
	}
}