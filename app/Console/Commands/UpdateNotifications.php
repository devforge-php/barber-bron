<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateNotifications extends Command
{
    protected $signature = 'notifications:update';
    protected $description = 'Har kuni 01:00 da notificationlarni active false va completed true qilib yangilaydi';

    public function handle()
    {
        DB::table('notifications')->update([
            'active' => false,
            'completed' => true,
        ]);

        $this->info("Barcha notificationlar yangilandi: active = false, completed = true");
    }
}
