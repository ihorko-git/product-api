<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $users = \App\Models\User::all();
        $reportViewsData = [];

        foreach ($users as $user) {
            $reportViewsData[] = [
                'product_id' => $product->id,
                'user_id' => $user->id,
                'total_views' => 0
            ];
        }

        \App\Models\ReportView::insert($reportViewsData);
    }
}
