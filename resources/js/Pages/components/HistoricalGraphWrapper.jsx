import { useEffect, useState } from "react";
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    Tooltip,
    ResponsiveContainer,
} from "recharts";
import { usePage } from "@inertiajs/react";

import {
    handleFilterChange,
    useFormatCurrency,
    formatDate,
} from "../helper.js";

export function HistoricalGraphWrapper() {
    const [isMounted, setIsMounted] = useState(false);

    const { top10Categories, categoryPrices, language } = usePage().props;

    const locale = language || "en";
    const periods = [
        { label: "7D", value: "1w", isShown: true },
        { label: "2W", value: "2w", isShown: true },
        { label: "1M", value: "1m", isShown: true },
        { label: "3M", value: "3m", isShown: false },
        { label: "6M", value: "6m", isShown: false },
        { label: "1Y", value: "1y", isShown: false },
    ];

    const { category_id, period } = Object.fromEntries(
        new URLSearchParams(window.location.search)
    );

    const handlePeriodFilterChange = (newPeriodFilter) => {
        handleFilterChange({ period: newPeriodFilter });
    };

    const handleCategoryIdFilterChange = (newCategoryIdFilter) => {
        handleFilterChange({ category_id: newCategoryIdFilter });
    };

    const formatCurrency = useFormatCurrency();

    useEffect(() => {
        setIsMounted(true);
    }, []);

    useEffect(() => {
        if (isMounted === true) {
            handleFilterChange({ period: "1w" });
        }
    }, [isMounted]);

    return (
        <div className="container">
            <div className="row">
                <div className="col">
                    <div className="card">
                        <div className="card-body">
                            <div className="card-title row">
                                <div
                                    className="col-4 d-flex align-items-center justify-content-between"
                                    style={{ gap: "10px" }}
                                >
                                    <h5 className="text-nowrap">
                                        Historical Report
                                    </h5>
                                    <select
                                        className="form-control"
                                        name="category_id"
                                        value={
                                            category_id !== undefined
                                                ? category_id
                                                : top10Categories[0].id
                                        }
                                        onChange={(e) =>
                                            handleCategoryIdFilterChange(
                                                e.target.value
                                            )
                                        }
                                    >
                                        {top10Categories.map(({ id, name }) => (
                                            <option key={id} value={id}>
                                                {name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="col d-flex align-items-center justify-content-end">
                                    {periods.map(
                                        ({ label, value, isShown }) => {
                                            return isShown === true ? (
                                                <button
                                                    key={value}
                                                    className="m-2 p-1 rounded btn btn-primary btn-sm text-white"
                                                    style={{
                                                        backgroundColor:
                                                            period !==
                                                                undefined &&
                                                            period === value
                                                                ? "var(--primary)"
                                                                : "#e4aa8f",
                                                    }}
                                                    onClick={() =>
                                                        handlePeriodFilterChange(
                                                            value
                                                        )
                                                    }
                                                >
                                                    {label}
                                                </button>
                                            ) : null;
                                        }
                                    )}
                                </div>
                            </div>
                            <ResponsiveContainer width="100%" height={400}>
                                <LineChart
                                    data={categoryPrices}
                                    margin={{
                                        top: 20,
                                        right: 30,
                                        left: 20,
                                        bottom: 5,
                                    }}
                                >
                                    <XAxis dataKey="date" />
                                    <YAxis tickFormatter={formatCurrency} />
                                    <Tooltip
                                        formatter={(value) =>
                                            formatCurrency(value)
                                        }
                                        labelFormatter={(label) =>
                                            formatDate(label, locale)
                                        }
                                    />
                                    <Line
                                        type="monotone"
                                        dataKey="price"
                                        stroke="#ff7300"
                                    />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
