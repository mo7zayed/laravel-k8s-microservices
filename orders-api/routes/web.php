<?php

use App\Models\Order;
use Illuminate\Http\Request;
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
            url('/orders'),
            url('/orders/create'),
        ]
    ];
});

Route::get('/orders', function (Request $request) {
    $request->validate([
        'user_id' => ['sometimes', 'integer'],
    ]);

    return Order::query()
        ->when($request->input('user_id'), fn($q) => $q->where('user_id', $request->input('user_id')))
        ->get();
});

Route::get('/orders/create', function (Request $request) {
    $request->validate([
        'user_id' => ['required', 'integer'],
    ]);

    return Order::factory($request->input('count', 1))->create([
        'user_id' => $request->input('user_id'),
    ]);
});
