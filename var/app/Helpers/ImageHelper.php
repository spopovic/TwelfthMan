<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Sujip\Guid\Facades\Guid;

class ImageHelper
{

    /**
     * Resize image and save to filesystem. Also store original one and create thumbnail
     *
     * @param $sourcePath
     * @param null $extension
     * @return string
     */
    public static function storeImage($sourcePath, $extension = null)
    {
        if (!$extension) {
            //Get extension name form source if not set
            $extension = File::extension($sourcePath);
        }

        $imageManager = new ImageManager();
        $img = $imageManager->make($sourcePath);

        //Get image config
        $dir = storage_path(config('image.images_dir'));
        $thumb_dir = storage_path(config('image.images_thumbnail_dir'));
        $original_dir = storage_path(config('image.images_original_dir'));
        $image_width = config('image.image_width');
        $image_height = config('image.image_height');
        $thumb_width = config('image.thumb_width');
        $thumb_height = config('image.thumb_height');

        //Generate unique name
        $name = self::createUniqueImageName($dir, Guid::create(), $extension);

        //Save original image
        $img->save($original_dir . $name);

        //Resize and save default image
        $img->resize($image_width, $image_height, function ($constraint) {
            //Keep aspect ratio
            $constraint->aspectRatio();
            //Forbide upscale
            $constraint->upsize();
        });
        $img->save($dir . $name);

        //Create and save thumbnail
        $img->fit($thumb_width, $thumb_height, function ($constraint) {
            //Keep aspect ratio
            $constraint->aspectRatio();
            //Forbide upscale
            $constraint->upsize();
        });
        $img->save($thumb_dir . $name);

        return $name;
    }

    /**
     * @param $path
     * @param $imageName
     * @param $extension
     * @return string
     */
    public static function createUniqueImageName($path, $imageName, $extension)
    {
        if (file_exists($path . $imageName . '.' . $extension)) {
            $imageName = Guid::create();
            return self::createUniqueImageName($path, $imageName, $extension);
        } else {
            return $imageName . '.' . $extension;
        }
    }

    /**
     * Remove image files
     *
     * @param $imageName
     */
    public static function removeImageFiles($imageName)
    {
        $dir = storage_path(config('image.images_dir'));
        $thumb_dir = storage_path(config('image.images_thumbnail_dir'));
        $original_dir = storage_path(config('image.images_original_dir'));

        if (file_exists($dir . $imageName)) {
            unlink($dir . $imageName);
        }

        if (file_exists($thumb_dir . $imageName)) {
            unlink($thumb_dir . $imageName);
        }

        if (file_exists($original_dir . $imageName)) {
            unlink($original_dir . $imageName);
        }
    }
}