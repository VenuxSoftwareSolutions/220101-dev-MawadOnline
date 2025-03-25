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

import { handleFilterChange } from "../helper.js";

export function HistoricalGraphWrapper() {
    const [isMounted, setIsMounted] = useState(false);

    const { top10Categories, categoryPrices } = usePage().props;

    const periods = [
        { label: "1 Week", value: "1w" },
        { label: "2 Weeks", value: "2w" },
        { label: "1 Month", value: "1m" },
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
                                <div className="col d-flex justify-content-between">
                                    <h5>Historical Report</h5>
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
                                <div className="d-flex justify-content-between">
                                    {periods.map(({ label, value }) => (
                                        <button
                                            key={value}
                                            className="m-2 px-1 btn btn-primary btn-sm text-white"
                                            style={{
                                                backgroundColor:
                                                    period !== undefined &&
                                                    period === value
                                                        ? "var(--primary)"
                                                        : "#e4aa8f",
                                            }}
                                            onClick={() =>
                                                handlePeriodFilterChange(value)
                                            }
                                        >
                                            {label}
                                        </button>
                                    ))}
                                </div>
                            </div>
                            <ResponsiveContainer width="100%" height={400}>
                                <LineChart data={categoryPrices}>
                                    <XAxis dataKey="date" />
                                    <YAxis />
                                    <Tooltip />
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
