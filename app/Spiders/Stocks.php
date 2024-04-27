<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use App\Models\Stock;

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

        $vals = [];
        $vals['S&P 500'] = $response->filter($selectors['S&P 500'])->text();
        $vals['Dow 30'] = $response->filter($selectors['Dow 30'])->text();
        $vals['Nasdaq'] = $response->filter($selectors['Nasdaq'])->text();
        $vals['Russell 2000'] = $response->filter($selectors['Russell 2000'])->text();

        $stocks = [];
        foreach ($vals as $stockName => $val) {
            if (empty($val)) {
                echo "$stockName is empty, skipping.\n\n";
                continue;
            } else {
                echo "$stockName val is $val, moving forward.\n\n";
            }

            $val = (float) str_replace(',', '', $val);
            if (!is_float($val)) {
                echo "We've removed commas and tried to convert to float.\n Result: $val\n Skipping $stockName.\n\n";
                continue;
            } else {
                echo "Removed commas and successfully converted $stockName to float. Result: $val.\n\n";
            }

            $stocks[] = Stock::udpatePrice($stockName, number_format($val, 2, '.', ''));
        }

        // sail artisan roach:run Stocks
        echo "Dumping stocks array...\n\n";
        var_dump($stocks);

        yield $this->item($stocks);
    }
}
