<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::collection('widgets')->delete();
        DB::collection('widgets')->insert([
            "widgetId" => 1,
            "IpBanList" => [ ],
            "color" => "#05b688",
            "countryBanList" => [ ],
            "domainBanList" => [ ],
            "isActive" => true,
            "name" => "Finals",
            "cityBanList" => [ ],
            "banListEnabled" => false,
            "hasSchedule" => false,
            "scheduleEnabled" => false,
            "whiteListEnabled" => false,
            "cityWhiteList" => [ ],
            "countryWhiteList" => [ ],
            "domainWhiteList" => [ ],
            "img_src" => "assets/images/widget-icon.png",
            "ipBanList" => [ ],
            "ipWhiteList" => [ ],
            "schedule" => 
                array(
                    array(
                        "id" => 1,
                        "day" => "Sunday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 2,
                        "day" => "Monday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 3,
                        "day" => "Tuesday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 4,
                        "day" => "Wednesday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 5,
                        "day" => "Thursday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 6,
                        "day" => "Friday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    ),
                    array(
                        "id" => 7,
                        "day" => "Saturday",    
                        "start_time" => "08:30",
                        "end_time" => "18:30",
                        "enabled" => true

                    )
                )
        ]);

    }
}
