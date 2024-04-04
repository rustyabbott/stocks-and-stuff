<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

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
        $sAndP500 = $response->filter('#marketsummary-itm-0 > h3:nth-child(1) > fin-streamer:nth-child(3)')->text();
        $dow30 = $response->filter('#marketsummary-itm-1 > h3:nth-child(1) > fin-streamer:nth-child(3)')->text();
        $nasdaq = $response->filter('#marketsummary-itm-2 > h3:nth-child(1) > fin-streamer:nth-child(3)')->text();
        $russell2000 = $response->filter('#marketsummary-itm-3 > h3:nth-child(1) > fin-streamer:nth-child(3)')->text();

        $quotes = [
            'S&P 500' => $sAndP500,
            'Dow 30' => $dow30,
            'Nasdaq' => $nasdaq,
            'Russell 2000' => $russell2000
        ];

        foreach ($quotes as $name => $price) {
            $quotes[$name] = $this->formatPriceForDatabase($price);
        }

        // php artisan roach:run Stocks
        var_dump($quotes);

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
