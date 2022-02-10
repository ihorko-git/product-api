<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function (Request $request) {
    $validator = \Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails() || !Auth::attempt($validator->valid())) {
        return response(null,401);
    }

    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer'
    ]);
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('products',
        'App\Http\Controllers\API\ProductController',
        ["except" => ["create", "edit"]]
    );

    Route::get('/logout', function (Request $request) {
        auth()->user()->tokens()->where('name', 'auth_token')->delete();
        return response(null, 200);
    });

    Route::get('/report/{date}', function (Request $request, $date) {
        $results = DB::select('select products.id, products.name, ifnull(rv.product_views, 0) as views, ifnull(rp.product_quantity, 0) as quantity from products left join (select product_id, sum(total_views) as product_views from report_views where date = ? group by product_id) as rv on products.id = rv.product_id left join (select product_id, sum(quantity) as product_quantity from report where date = ? group by product_id) as rp on products.id = rp.product_id',
            [$date, $date]);

        return response()->json($results);
    })->where('date', '\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])');
});
