import { handleFilterChange } from "../../helper";

export function PeriodsButtonsFilter() {
    const periods = [
        { label: "1W", value: "1w", isShown: true },
        { label: "2W", value: "2w", isShown: true },
        { label: "1M", value: "1m", isShown: true },
        { label: "3M", value: "3m", isShown: false },
        { label: "6M", value: "6m", isShown: false },
        { label: "1Y", value: "1y", isShown: false },
    ];

    const { period } = Object.fromEntries(
        new URLSearchParams(window.location.search)
    );
    const handlePeriodFilterChange = (newPeriodFilter) => {
        handleFilterChange({ period: newPeriodFilter });
    };

    return periods.map(({ label, value, isShown }) => {
        return isShown === true ? (
            <button
                key={value}
                className="m-2 p-1 rounded btn btn-primary btn-sm text-white"
                style={{
                    backgroundColor:
                        period !== undefined && period === value
                            ? "var(--primary)"
                            : "#e4aa8f",
                }}
                onClick={() => handlePeriodFilterChange(value)}
            >
                {label}
            </button>
        ) : null;
    });
}
