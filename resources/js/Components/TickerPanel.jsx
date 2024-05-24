const TickerPanel = ({ stock }) => {
    const bgColor = stock.curr_price >= stock.prev_price ? 'bg-green-400 dark:bg-green-700' : 'bg-red-700';
    const priceDiff = stock.curr_price - stock.prev_price;
    const plusOrMinus = priceDiff >= 0 ? '+' : '';

    const currentPrice = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(stock.curr_price);

    const change = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(priceDiff);

    return (
        <div className={`overflow-hidden outline-dotted h-[100px] ${bgColor} text-white p-2`}>
            <div className="text-2xl">
                { stock.stock_name }
            </div>
            <div>
                { currentPrice }
            </div>
            <div>
                { plusOrMinus + change }
            </div>
        </div>
    );
}

export default TickerPanel;