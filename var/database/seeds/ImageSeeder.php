<?php

use Illuminate\Database\Seeder;
use App\Image;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('import:images --seed_document="true"');
        } catch (\Exception $e) {
            $this->info("Error " . $e->getMessage()."\n");
        }
    }
}
