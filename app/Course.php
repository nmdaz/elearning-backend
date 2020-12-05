<?php

namespace App;

use App\User;
use App\Section;
use App\Includes\DriveStorageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;


class Course extends Model
{
    protected $storageHelper;

    protected $fillable = [
        'name', 
        'cover_image', 
        'description', 
        'attachment', 
        'author_id'
    ];

    function __construct($attributes = array())
    {
        $this->storageHelper = resolve(DriveStorageHelper::class);
        parent::__construct($attributes);
    }
    
    public function setCoverImageAttribute(UploadedFile $value)
    {
        $path = $this->storageHelper->put($value);

        if ($path)  {
            $this->attributes['cover_image'] = $path;
        }
    }

    public function setAttachmentAttribute(UploadedFile $value)
    {
        $path = $this->storageHelper->put($value);

        if ($path) {
            $this->attributes['attachment'] = $path;
        }
    }

    public function removeAttachment()
    {
        if (!$this->attributes['attachment']) {
            return false;
        }

        if ($this->storageHelper->delete($this->attributes['attachment'])) {
            $this->attributes['attachment'] = null;
            return true;
        }

        return false;
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
