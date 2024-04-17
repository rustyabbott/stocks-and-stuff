<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * Updates stock curr_price and prev_price
     * 
     * @param  int   $stockName  ID in the stocks table
     * @param  float $newPrice   Stock price, two decimal place, no commas/separators
     * @return Stock $stock      Object, everything from stocks table after update
     */
    public static function udpatePrice($stockName, $newPrice) {
        $stock = Stock::where('stock_name', '=', $stockName)->firstOrFail();
        $stock->prev_price = $stock->curr_price;
        $stock->curr_price = $newPrice;
        $stock->save();

        return $stock;
    }
}
