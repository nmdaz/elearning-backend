<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RefreshDatabaseOnce extends TestCase
{
    //use this test to refresh database so other test
    //can use DatabaseTransaction to speed up testing
    public function test_refresh_database()
    {
        Artisan::call('migrate:refresh');
        $this->assertTrue(true);
    }
}
