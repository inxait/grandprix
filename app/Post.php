<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'excerpt', 'description', 'slug', 'image', 'published',
        'related_material', 'type_related_material'
    ];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public static function typeRelatedMaterial($file)
    {
      $extention = $file->getClientOriginalExtension();
      if ($extention == "pdf" || $extention == "docx") {
        $type = "document";
      }
      if ($extention == "jpg" || $extention == "png" || $extention == "jpeg" || $extention == "gif") {
        $type = "image";
      }
      if ($extention == "JPEG" || $extention == "PNG") {
        $type = "image";
      }
      if ($extention == "mp4" || $extention == "avi" || $extention == "mov" || $extention == "mpeg") {
        $type = "video";
      }
      if ($extention == "JPEG" || $extention == "PNG") {
        $type = "image";
      }

      if (!isset($type)) {
        $type = "not";
      }
      return $type;
    }
}
