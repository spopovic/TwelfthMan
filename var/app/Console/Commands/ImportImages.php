<?php

namespace App\Console\Commands;

use App\Helpers\ImageHelper;
use App\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Facades\Zipper;
use Sujip\Guid\Facades\Guid;
use Validator;

ini_set('memory_limit', '1024M');

class ImportImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:images {--seed_document=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Images from zip file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $seed_document = $this->option('seed_document');

        $public_images_path = public_path(config('image.public_images_path'));
        File::isDirectory($public_images_path) || File::makeDirectory($public_images_path, 0644, true, true);

        $base_path = storage_path(config('image.storage_images_path'));
        File::isDirectory($base_path) || File::makeDirectory($base_path, 0644, true, true);

        $gallery_dir = storage_path(config('image.images_dir'));
        $thumb_dir = storage_path(config('image.images_thumbnail_dir'));
        $original_dir = storage_path(config('image.images_original_dir'));

        File::isDirectory($gallery_dir) || File::makeDirectory($gallery_dir, 0644, true, true);
        File::isDirectory($original_dir) || File::makeDirectory($original_dir, 0644, true, true);
        File::isDirectory($thumb_dir) || File::makeDirectory($thumb_dir, 0644, true, true);

        $files = $seed_document ? File::files(database_path('/seeds/images/')) : File::files($public_images_path);

        $temp_folder_path = storage_path(config('image.temp_storage_images_path'));
        File::isDirectory($temp_folder_path) || File::makeDirectory($temp_folder_path, 0644, true, true);
        $processed_folder_path = storage_path(config('image.processed_storage_images_path'));
        File::isDirectory($processed_folder_path) || File::makeDirectory($processed_folder_path, 0644, true, true);

        if(!$files){
            $this->info("\n" . "Any appropriate file is not founded." . "\n" ."Please paste .zip file to the $public_images_path" . "\n");
            exit;
        }

        foreach ($files as $file) {
            $extension = File::extension($file);
            if($extension !== 'zip') continue;

            $temp_sub_folder_path = self::createUniqueDirectory(storage_path(config('image.temp_storage_images_path')), Guid::create());
            $processed_sub_folder_path = self::createUniqueDirectory(storage_path(config('image.processed_storage_images_path')), Guid::create());

            $zipper = Zipper::make($file);
            $zipper->extractTo($temp_sub_folder_path);
            $zipper->close();

            $file_name = File::name($file);

            if ($seed_document){
                File::copy(database_path('/seeds/images/') . $file_name . '.' . $extension, $processed_sub_folder_path . '/' . $file_name . '.' . $extension);
            } else {
                File::move($public_images_path . $file_name . '.' . $extension, $processed_sub_folder_path . '/' . $file_name . '.' . $extension);
            }

            $this->info("\n" . "Importing files from $file_name.$extension" . "\n");

            self::importImagesForEachTempFolder($temp_sub_folder_path, $file_name, $extension);
        }
    }

    /**
     * @param string $dir
     * @return bool
     */
    private static function delFiles($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delFiles("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * @param $path
     * @param $name
     * @return string
     */
    private static function createUniqueDirectory($path, $name)
    {
        if (File::isDirectory($path . $name)) {
            $name = Guid::create();;
            return self::createUniqueDirectory($path, $name);
        } else {
            File::makeDirectory($path . $name, 0644, true, true);
            return $path . $name;
        }
    }

    /**
     * @param $temp_sub_folder_path
     */
    private function importImagesForEachTempFolder($temp_sub_folder_path, $file_name, $extension){
        $images = File::AllFiles($temp_sub_folder_path);
        $counter = 0;
        foreach ($images as $image_from_file) {
            $image_extension = File::extension($image_from_file);
            $imageFilename = '';
            try {
                $imageFilename = ImageHelper::storeImage($image_from_file, $image_extension);
            } catch (\Exception $e) {
                $this->info("Skipped {$image_from_file}." . "\n" . "Problem: " . $e->getMessage() . "\n");
            }

            $counter = self::importSingleImageAndIncreaseNumberImages($image_from_file, $image_extension, $imageFilename, $counter);
        }
        if ($counter > 0)
            $this->info("Imported {$counter} images from $file_name.$extension" . "\n");

        self::delFiles($temp_sub_folder_path);
    }

    /**
     * @param $image_from_file
     * @param $image_extension
     * @param $counter
     * @return mixed
     */
    private function importSingleImageAndIncreaseNumberImages($image_from_file, $image_extension, $image_filename, $counter){
        $data['name'] = File::name($image_from_file) . '.' . $image_extension;
        if (empty($image_filename))
            return $counter;

        $validator = Validator::make($data, [
            'name' => 'required|max:100',
        ]);
        if (!$validator->fails()) {
            try {
                $image = new Image();
                $image->path = $image_filename;
                $image->name = $data['name'];
                if ($image->save()) {
                    $counter++;
                } else {
                    ImageHelper::removeImageFiles($image_filename);
                }
            } catch (\Exception $e) {
                $this->info("Image {$image_filename}." . "isn't saved" . "\n" . "Problem: " . $e->getMessage() . "\n");
            }
        } else {
            $this->info("Validation problem with {$data['name']}; Error: " . $validator->errors() . "\n");
        }
        return $counter;
    }
}
