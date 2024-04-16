<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use Illuminate\Support\Facades\DB;

class Stocks extends BasicSpider
{
    public array $startUrls = [
        'https://finance.yahoo.com'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $selectors = [
            'S&P 500' => 'li.box-item:nth-child(1) > a:nth-child(1) > div:nth-child(1) > span:nth-child(3) > fin-streamer:nth-child(1) > span:nth-child(1)',
            'Dow 30' => 'li.box-item:nth-child(2) > a:nth-child(1) > div:nth-child(1) > span:nth-child(3) > fin-streamer:nth-child(1) > span:nth-child(1)',
            'Nasdaq' => 'li.box-item:nth-child(3) > a:nth-child(1) > div:nth-child(1) > span:nth-child(3) > fin-streamer:nth-child(1) > span:nth-child(1)',
            'Russell 2000' => 'li.box-item:nth-child(4) > a:nth-child(1) > div:nth-child(1) > span:nth-child(3) > fin-streamer:nth-child(1) > span:nth-child(1)'
        ];

        if (!empty($response->filter($selectors['S&P 500'])->text())) {
            $sAndP500 = $response->filter($selectors['S&P 500'])->text();
        }
        
        if (!empty($response->filter($selectors['Dow 30'])->text())) {
            $dow30 = $response->filter($selectors['Dow 30'])->text();
        }

        if (!empty($response->filter($selectors['Nasdaq'])->text())) {
            $nasdaq = $response->filter($selectors['Nasdaq'])->text();
        }

        if (!empty($response->filter($selectors['Russell 2000'])->text())) {
            $russell2000 = $response->filter($selectors['Russell 2000'])->text();
        }

        $quotes = [];
        !empty($sAndP500) ? $quotes['S&P 500'] = $sAndP500 : '';
        !empty($dow30) ? $quotes['Dow 30'] = $dow30 : '';
        !empty($nasdaq) ? $quotes['Nasdaq'] = $nasdaq : '';
        !empty($russell2000) ? $quotes['Russell 2000'] = $russell2000 : '';

        var_dump($quotes);

        if (empty($quotes)) {
            die('quotes is empty, exiting.');
        }

        // sail shell -> php artisan roach:run Stocks
        var_dump($quotes);

        foreach ($quotes as $name => $price) {
            DB::table('stocks')
                ->where('stock_name', $name)
                ->update(['curr_price' => $this->formatPriceForDatabase($price)]);
        }

        yield $this->item($quotes);
    }

    /**
     * Prepare scraped stock prices for insert into database
     * 
     * @param  string $price  Stock price with commas e.g. 12,000.50
     * @return float          Stock price without commas e.g. 12000.50
     */
    private function formatPriceForDatabase($price) {
        $price = str_replace(',', '', $price);
        return (float) number_format($price, 2, '.', '');
    }
}
