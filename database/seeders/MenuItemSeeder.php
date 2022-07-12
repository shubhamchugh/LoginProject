<?php

namespace Database\Seeders;

use App\Models\NovaMenuMenu;
use Illuminate\Database\Seeder;
use App\Models\NovaMenuMenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $records = NovaMenuMenu::first();

        if (!$records) {
            NovaMenuMenu::firstOrCreate([
                'name' => 'Home',
                'slug' => 'header',
            ]);
        }

        $records_menu = NovaMenuMenuItem::first();

        if (!$records_menu) {
            NovaMenuMenuItem::firstOrCreate([
                'menu_id'   => (!empty($records->id)) ? $records->id : 1,
                'name'      => 'Home',
                'locale'    => 'en_US',
                'class'     => 'Outl1ne\MenuBuilder\MenuItemTypes\MenuItemTextType',
                'value'     => null,
                'target'    => '_self',
                'data'      => null,
                'parent_id' => null,
                'order'     => 1,
                'enabled'   => 1,
            ]);
        }

    }
}
