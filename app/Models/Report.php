<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * Singular table name is against conventions
     *
     * @var string
     */
    protected $table = 'report';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
