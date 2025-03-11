import {
    LineChart,
    Line,
    Tooltip,
    ResponsiveContainer,
    XAxis,
    YAxis,
} from "recharts";

export default function CustomLineChart({ data }) {
    return (
        <ResponsiveContainer width="100%" height={150}>
            <LineChart data={data}>
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <Line dataKey="price" stroke="#ff7300" strokeWidth={3} />
            </LineChart>
        </ResponsiveContainer>
    );
}
