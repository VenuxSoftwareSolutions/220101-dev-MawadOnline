import { LineChart, Line, ResponsiveContainer } from "recharts";

export function SparklineChart({ data }) {
    return (
        <ResponsiveContainer>
            <LineChart data={data.map((y, x) => ({ x, y }))}>
                <Line
                    type="monotone"
                    dataKey="y"
                    stroke={data[0] > data[data.length - 1] ? "red" : "green"}
                    strokeWidth={2}
                />
            </LineChart>
        </ResponsiveContainer>
    );
}
