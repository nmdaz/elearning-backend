<?php

namespace Tests\Unit\includes;

use App\Includes\YoutubeIdExtractor;
use PHPUnit\Framework\TestCase;

class YoutubeIdExtractorTest extends TestCase
{
	private $extractor;

	public function setUp() :void
	{
		parent::setUp();
		$this->extractor = new YoutubeIdExtractor();
	}

    public function test_extract_valid_youtube_id()
    {
    	$extractor = $this->extractor;

    	$youtubeId = $extractor->extractId('http://youtu.be/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/embed/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/watch?v=dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/v/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/e/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');

    	$youtubeId = $extractor->extractId('http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ');
    	$this->assertSame($youtubeId, 'dQw4w9WgXcQ');
    }

    public function test_return_null_on_invalid_youtube_id()
    {
    	$youtubeId = $this->extractor->extractId('http://www.yutube.com/?feature=player_embedded&v=dQw4w9WgXcQ');

    	$this->assertSame($youtubeId, null);

    	$youtubeId = $this->extractor->extractId('http://youatu.be/dQw4w9WgXcQ');

    	$this->assertSame($youtubeId, null);
    }
}
