<?php

use Illuminate\Support\Facades\Storage;

Route::get('test', function() {
    Storage::disk('google')->put('test.txt', 'Hello World');
});