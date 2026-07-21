<?php

use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\Category\Create;
use App\Livewire\Admin\Category\Edit;
use App\Livewire\Admin\Category\Index;
use App\Livewire\Admin\Menu\Create as MenuCreate;
use App\Livewire\Admin\Menu\Edit as MenuEdit;
use App\Livewire\Admin\Menu\Index as MenuIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Components\Sidebar;
use App\Livewire\Guest\Home;
use Illuminate\Support\Facades\Route;


Route::get('/', Home::class);

Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])
    ->name('payment.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class]);

Route::get('/order/track/{orderCode}', \App\Livewire\Guest\Order\Track::class)->name('guest.order.track');
Route::get('/order/{tableSlug}', \App\Livewire\Guest\Menu\Index::class)->name('guest.menu.index');
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    // Bisa diakses oleh siapapun yang sudah login (Admin maupun Kepala)
    Route::post('/logout', App\Livewire\Components\Sidebar::class . '@logout')->name('logout');

    // === KHUSUS ADMIN (Fokus Operasional) ===
    Route::middleware('can:admin')->group(function () {
        Route::get('/categories/index', App\Livewire\Admin\Category\Index::class)->name('categories.index');
        Route::get('/categories/create', App\Livewire\Admin\Category\Create::class)->name('categories.create');
        Route::get('/categories/{id}/edit', App\Livewire\Admin\Category\Edit::class)->name('categories.edit');

        Route::get('/menu/index', App\Livewire\Admin\Menu\Index::class)->name('menu.index');
        Route::get('/menu/create', App\Livewire\Admin\Menu\Create::class)->name('menu.create');
        Route::get('/menu/{id}/edit', App\Livewire\Admin\Menu\Edit::class)->name('menu.edit');

        Route::get('/add-on/index', \App\Livewire\Admin\AddOn\Index::class)->name('add-on.index');
        Route::get('/add-on/create', \App\Livewire\Admin\AddOn\Create::class)->name('add-on.create');
        Route::get('/add-on/{id}/edit', \App\Livewire\Admin\AddOn\Edit::class)->name('add-on.edit');

        Route::get('/orders/index', \App\Livewire\Admin\Orders\Index::class)->name('orders.index');

        Route::get('/meja/index', \App\Livewire\Admin\Meja\Index::class)->name('meja.index');
        Route::get('/meja/create', \App\Livewire\Admin\Meja\Create::class)->name('meja.create');
        Route::get('/meja/{id}/edit', \App\Livewire\Admin\Meja\Edit::class)->name('meja.edit');

        Route::get('/riwayat/index', \App\Livewire\Admin\Riwayat\Index::class)->name('riwayat.index');
    });

    // === KHUSUS KEPALA TOKO (Fokus Pantauan) ===
    Route::middleware('can:kepala')->group(function () {
        Route::get('/dashboard/index', \App\Livewire\Admin\Dashboard\Index::class)->name('dashboard');
        Route::get('/report/index', \App\Livewire\Admin\Report\Index::class)->name('report.index');
    });
});
