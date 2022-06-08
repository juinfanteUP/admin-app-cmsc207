<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WidgetIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::collection('widget_icons')->delete();

        DB::collection('widget_icons')->insert(["id" => 100, "name" => "Default", "img_src" => "assets/images/widget-icon.png"]);
        DB::collection('widget_icons')->insert(["id" => 1, "name" => "Leaves", "img_src" => "assets/images/widgets/widget-01-leaves.png"]);
        DB::collection('widget_icons')->insert(["id" => 2, "name" => "Flower", "img_src" => "assets/images/widgets/widget-02-flower.png"]);
        DB::collection('widget_icons')->insert(["id" => 3, "name" => "Centrifugal", "img_src" => "assets/images/widgets/widget-03-centrifugal.png"]);
        DB::collection('widget_icons')->insert(["id" => 4, "name" => "Feather", "img_src" => "assets/images/widgets/widget-04-feather.png"]);
        DB::collection('widget_icons')->insert(["id" => 5, "name" => "Person", "img_src" => "assets/images/widgets/widget-05-person.png"]);
        DB::collection('widget_icons')->insert(["id" => 6, "name" => "Hand", "img_src" => "assets/images/widgets/widget-06-hand.png"]);       
    }
}
