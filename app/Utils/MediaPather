<?php

namespace App\Utils;

class MediaPather
{
    public static function getMediaUrl($path)
    {
        return asset('media/' . $path);
    }
    
    public static function getAllFromFolder($folder)
    {
        $path = public_path('media/' . $folder);
        if (!is_dir($path)) return [];
        
        $files = scandir($path);
        return array_values(array_filter($files, function($file) use ($path) {
            return !in_array($file, ['.', '..']) && !is_dir($path.'/'.$file);
        }));
    }
}
