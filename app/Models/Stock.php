<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_name',
        'curr_price',
        'prev_price'
    ];

    /**
     * Updates stock curr_price and prev_price
     * 
     * @param  string $stockName  Name of the stock
     * @param  float  $newPrice   Stock price, two decimal place, no commas/separators
     * @return Stock  $stock      Object, everything from stocks table after update
     */
    public static function udpatePrice($stockName, $newPrice) {
        $stock = Stock::firstOrCreate(
            ['stock_name' => $stockName],
            ['curr_price' => $newPrice, 'prev_price' => null]
        );
    
        if (!$stock->wasRecentlyCreated) {
            $stock->prev_price = $stock->curr_price;
            $stock->curr_price = $newPrice;
            $stock->save();
        }

        return $stock;
    }
}
