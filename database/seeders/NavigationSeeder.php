<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Home', '/', 10], ['About', '/#about', 20], ['Services', '/services', 30],
            ['Agencies', '/agencies', 40], ['News & Notices', '/news-notices', 50], ['Documents', '/documents', 60],
        ];
        foreach ($items as [$label, $destination, $position]) {
            NavigationItem::firstOrCreate(['location' => 'primary', 'label' => $label], compact('destination', 'position') + ['is_visible' => true]);
        }
    }
}
