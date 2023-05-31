<?php

namespace Database\Seeders;

use App\Models\LocationTag;
use App\Models\LocationTagLocation;
use App\Models\Payments\Paysystem;
use Illuminate\Database\Seeder;

class LocationsTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations_tags = LocationTag::select()->get();

        foreach($locations_tags as $duplicate_tag) {
            $original_tag = LocationTag::select()->whereRaw("id < {$duplicate_tag->id} and tag = '{$duplicate_tag->tag}'")->first();
            $new_location_tag_link = new LocationTagLocation();

            // Если текущий тег - оригинал
            if (empty($original_tag)) {
                $new_location_tag_link->location_id = $duplicate_tag->location_id;
                $new_location_tag_link->tag_id = $duplicate_tag->id;
                $new_location_tag_link->save();
            } else { // если дубликат
                $this->command->info("Original: {$original_tag->id}, {$original_tag->tag}");
                $this->command->info("Duplicate: {$duplicate_tag->id}, {$duplicate_tag->tag}");

                $new_location_tag_link->location_id = $duplicate_tag->location_id;
                $new_location_tag_link->tag_id = $original_tag->id;
                $new_location_tag_link->save();

                LocationTag::where('id',$duplicate_tag->id)->delete();

            }
        }
    }
}
