<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Определяем расписание команд.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Запуск команды news:fetch каждые 30 минут
        $schedule->command('news:fetch')->everyThirtyMinutes();
        // Можешь поменять на ->hourly(), ->everyTenMinutes(), ->daily() и т.д.
    }

    /**
     * Зарегистрировать команды приложения.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
