<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price'];

    public function getPriceAttribute(int $value)
    {
        return $value / 100;
    }

    public function setPriceAttribute(float $value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function reportViews()
    {
        return $this->hasMany(ReportView::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // probably might be some better solution, for example to avoid static usage... but upsert...
    // and $date parameter requires type to be specified
    // or separate table with 1 view per row... if we r rich and can afford such thing
    public static function increaseViews(Collection|Product $products, User $user, $date, int $count = 1) {
        if($products instanceof Product) {
            $products = collect([$products]);
        }

        $userId = $user->id;

        $reportViews = ReportView::where([
            'user_id' => $userId,
            'date' => $date
        ])->whereIn('product_id', $products->pluck('id'))
            ->get();

        $reportViewsData = [];
        foreach ($reportViews as $reportView) {
            $reportViewsData[] = [
                'id' => $reportView->id,
                'product_id' => $reportView->product_id,
                'user_id' => $userId,
                'date' => $date,
                'total_views' => $reportView->total_views + 1
            ];
        }

        $productIdsOfAbsentReports = array_diff(
            $products->pluck('id')->toArray(),
            $reportViews->pluck('product_id')->toArray()
        );

        foreach ($productIdsOfAbsentReports as $productId) {
            $reportViewsData[] = [
                'id' => null,
                'product_id' => $productId,
                'user_id' => $userId,
                'date' => $date,
                'total_views' => 1
            ];
        }

        \App\Models\ReportView::upsert($reportViewsData, ['product_id', 'user_id', 'date'], ['total_views']);
    }
}
