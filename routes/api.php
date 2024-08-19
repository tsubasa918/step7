<?php

use App\Http\Controllers\Api\PurchaseController;

Route::post('purchase', [PurchaseController::class, 'purchase']);
