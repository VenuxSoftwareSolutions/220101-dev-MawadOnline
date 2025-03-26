import { usePage } from "@inertiajs/react";
import {
    LineChart,
    Line,
    Tooltip,
    ResponsiveContainer,
    XAxis,
    YAxis,
} from "recharts";

import { useFormatCurrency, formatDate } from "../helper.js";

export function CustomLineChart({ data }) {
    const { language } = usePage().props;
    const locale = language || "en";

    const formatCurrency = useFormatCurrency();

    return (
        <ResponsiveContainer width="100%" height={150}>
            <LineChart
                data={data}
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
                    labelFormatter={(label) => formatDate(label, locale)}
                    formatter={(value) => formatCurrency(value)}
                />
                <Line dataKey="price" stroke="#ff7300" strokeWidth={3} />
            </LineChart>
        </ResponsiveContainer>
    );
}
