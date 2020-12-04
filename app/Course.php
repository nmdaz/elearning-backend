<?php

namespace App;

use App\User;
use App\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;


class Course extends Model
{
    protected $fillable = ['name', 'cover_image', 'description', 'attachment', 'author_id'];
    
    public function setCoverImageAttribute(UploadedFile $value)
    {
        if (App::environment('production')) {
            $path = Storage::cloud()->putFile('/', $value);
            $this->attributes['cover_image'] = $path;
        } else {
            $path = Storage::disk('public')->putFile('covers', $value);
            $this->attributes['cover_image'] = $path;
        }
    }

    public function setAttachmentAttribute(UploadedFile $value)
    {
        if (App::environment('production')) {
            $path = Storage::disk('google')->putFile('/', $value);
            $this->attributes['attachment'] = $path;
        } else {
            $path = Storage::disk('public')->putFile('attachments', $value);
            $this->attributes['attachment'] = $path;
        }     
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function users()
    {
    	return $this->belongsToMany(User::class);
    }

    public function sections()
    {
    	return $this->hasMany(Section::class);
    }
}
