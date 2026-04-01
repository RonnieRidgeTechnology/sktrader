<?php

namespace Database\Seeders;

use App\Models\VideoReel;
use Illuminate\Database\Seeder;

class VideoReelSeeder extends Seeder
{
    public function run(): void
    {
        $reels = [];
        for ($i = 1; $i <= 10; $i++) {
            $reels[] = [
                'title'      => 'Reel ' . $i,
                'file_path'  => $i . '.mp4',
                'sort_order' => $i,
                'status'     => true,
            ];
        }
        foreach ($reels as $reel) {
            VideoReel::updateOrCreate(
                ['file_path' => $reel['file_path']],
                $reel
            );
        }
    }
}
