<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            create table ratios as (
                select products.id, products.name,
                       if(rv.product_views is null or rv.product_views = 0,
                           0,
                           ifnull(rp.product_purchases, 0)/rv.product_views) as percentage
                from products
                    left join (select product_id, sum(total_views) as product_views
                    from report_views group by product_id
                        ) as rv
                        on products.id = rv.product_id
                    left join (select product_id, count(*) as product_purchases
                    from report group by product_id
                        ) as rp
                        on products.id = rp.product_id
                )
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratios');
    }
}
