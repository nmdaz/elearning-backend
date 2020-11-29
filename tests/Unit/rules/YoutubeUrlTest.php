<?php

namespace Tests\Unit\rules;

use App\Includes\YoutubeIdExtractor;
use App\Rules\YoutubeUrl;
use PHPUnit\Framework\TestCase;

class YoutubeUrlTest extends TestCase
{
	private $rule;

	public function setUp() :void 
	{
		parent::setUp();
		$this->rule = new YoutubeUrl(new YoutubeIdExtractor);
	}

    public function test_correct_value_pass_test()
    {
    	$rule = $this->rule;

    	$this->assertTrue($rule->passes('url', 'http://youtu.be/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/embed/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/watch?v=dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/v/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/e/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ'));
    	$this->assertTrue($rule->passes('url', 'http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ'));
    }

    public function test_wrong_value_failed_validation()
    {
    	$this->assertFalse($this->rule->passes('url', 'http://yoaaautus.bedsd/dQw4w9WgXcQ'));
    }
}
