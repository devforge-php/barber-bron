<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClearYesterdayWorkSchedules extends Command
{
    protected $signature = 'work-schedules:clear-yesterday';
    protected $description = 'Kechagi ish vaqtlarini avtomatik o‘chirish';

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString(); // 2025-05-29 ko‘rinishida

        DB::table('work_schedules')
            ->where('date', $yesterday)
            ->delete();

        $this->info("Kechagi ish kunlari o‘chirildi: $yesterday");
    }
}
