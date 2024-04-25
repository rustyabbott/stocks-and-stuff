const TickerPanel = ({ stock }) => {
    return (
        <div className="outline-dotted h-[150px]">
            { stock.stock_name }<br />
            { stock.curr_price }
        </div>
    );
}

export default TickerPanel;