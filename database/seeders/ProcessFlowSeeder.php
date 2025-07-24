<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProcessFlowSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $stages = ['assembly', 'painting', 'inspection', 'packaging'];
        $items = [
            [
                'item_name' => 'Car Model A',
                'current_stage' => 'retail', // was 'packaging'
                'status' => 'completed',
                'entered_stage_at' => $now->copy()->subDays(5)->setTime(8, 0),
                'completed_stage_at' => $now->copy()->subDays(5)->setTime(16, 0),
                'failure_reason' => null,
                'updated_at' => $now->copy()->subDays(5)->setTime(16, 0),
            ],
            [
                'item_name' => 'Car Model B',
                'current_stage' => 'quality_control', // was 'inspection'
                'status' => 'failed',
                'entered_stage_at' => $now->copy()->subDays(4)->setTime(9, 0),
                'completed_stage_at' => null,
                'failure_reason' => 'Paint defect',
                'updated_at' => $now->copy()->subDays(4)->setTime(13, 0),
            ],
            [
                'item_name' => 'Car Model C',
                'current_stage' => 'manufacturing', // was 'painting'
                'status' => 'completed',
                'entered_stage_at' => $now->copy()->subDays(3)->setTime(10, 0),
                'completed_stage_at' => $now->copy()->subDays(3)->setTime(18, 0),
                'failure_reason' => null,
                'updated_at' => $now->copy()->subDays(3)->setTime(18, 0),
            ],
            [
                'item_name' => 'Car Model D',
                'current_stage' => 'manufacturing', // was 'assembly'
                'status' => 'completed',
                'entered_stage_at' => $now->copy()->subDays(2)->setTime(7, 0),
                'completed_stage_at' => $now->copy()->subDays(2)->setTime(15, 0),
                'failure_reason' => null,
                'updated_at' => $now->copy()->subDays(2)->setTime(15, 0),
            ],
            [
                'item_name' => 'Car Model E',
                'current_stage' => 'quality_control', // was 'inspection'
                'status' => 'failed',
                'entered_stage_at' => $now->copy()->subDays(1)->setTime(11, 0),
                'completed_stage_at' => null,
                'failure_reason' => 'Assembly error',
                'updated_at' => $now->copy()->subDays(1)->setTime(14, 0),
            ],
            [
                'item_name' => 'Car Model F',
                'current_stage' => 'distribution', // was 'packaging'
                'status' => 'completed',
                'entered_stage_at' => $now->copy()->setTime(8, 0),
                'completed_stage_at' => $now->copy()->setTime(17, 0),
                'failure_reason' => null,
                'updated_at' => $now->copy()->setTime(17, 0),
            ],
        ];
        foreach ($items as $item) {
            DB::table('process_flows')->insert(array_merge($item, [
                'created_at' => $item['entered_stage_at'],
            ]));
        }
    }
} 