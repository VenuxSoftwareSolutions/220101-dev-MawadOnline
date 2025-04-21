import { memo } from "react";
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    Tooltip,
    ResponsiveContainer,
} from "recharts";
import { usePage } from "@inertiajs/react";
import { motion } from "framer-motion";

import { useFormatCurrency, formatDate } from "../../helper.js";

export function LineChartWrapper() {
    const { categoryPrices, language } = usePage().props;

    const locale = language || "en";

    const formatCurrency = useFormatCurrency();

    return (
        <motion.div
            key={JSON.stringify(categoryPrices)}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ scale: 2, transition: { duration: 0.2 } }}
            transition={{ duration: 0.5, ease: "easeOut", delay: 0.1 }}
        >
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
                    <XAxis
                        dataKey="date"
                        tickFormatter={(label) => formatDate(label, locale)}
                    />
                    <YAxis tickFormatter={formatCurrency} />
                    <Tooltip
                        formatter={(value) => formatCurrency(value)}
                        labelFormatter={(label) => formatDate(label, locale)}
                    />
                    <Line type="monotone" dataKey="price" stroke="#ff7300" />
                </LineChart>
            </ResponsiveContainer>
        </motion.div>
    );
}

export const MemoisedLineChartWrapper = memo(
    LineChartWrapper,
    (prevProps, nextProps) => {
        return (
            JSON.stringify(prevProps.data) === JSON.stringify(nextProps.data)
        );
    }
);
