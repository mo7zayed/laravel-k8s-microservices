<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return [
        'app' => config('app.name'),
        'version' => app()->version(),
        'endpoints' => [
            url('/users'),
            url('/users-factory-create'),
            url('/users/1/orders'),
        ]
    ];
});

Route::get('/users', function () {
    return User::all();
});

Route::get('/users-factory-create', function (Request $request) {
    User::factory($request->input('count', 1))->create();

    return redirect()->to('/users');
});

Route::get('/users/{user}/orders', function (User $user) {
    config([
        'services.orders_api.base_url' => 'http://orders-api',
    ]);

    Http::macro('ordersApi', function () {
        return Http::acceptJson()->asJson()->baseUrl(config('services.orders_api.base_url'));
    });

    return Http::ordersApi()->get('/orders', [
        'user_id' => $user->id,
    ])->json();
});
