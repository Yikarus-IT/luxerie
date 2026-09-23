<?php

use App\Services\CheckoutService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:release-expired', function (CheckoutService $checkout) {
    $this->info($checkout->releaseExpired().' reservaciones vencidas liberadas.');
})->purpose('Libera inventario de pedidos cuyo pago no se completó a tiempo');

Schedule::command('orders:release-expired')->everyFiveMinutes()->withoutOverlapping();
