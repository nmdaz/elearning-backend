<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    public static $wrap = "comments";

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
