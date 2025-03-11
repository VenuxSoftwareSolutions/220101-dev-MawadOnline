import { useEffect, useState } from "react";
import { DataGrid } from "@mui/x-data-grid";
import { Box, TextField } from "@mui/material";
import { usePage } from "@inertiajs/react";

import { SparklineChart } from "./SparklineChart";
import { SpinnerWrapper } from "./SpinnerWrapper";

export default function MaterialsTrendsContainer() {
    const { props } = usePage();
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState([]);
    const [search, setSearch] = useState("");
    const [filteredRows, setFilteredRows] = useState([]);

    const columns = [
        { field: "id", headerName: "#", width: 50 },
        { field: "subcategory", headerName: "Sub category", flex: 1 },
        { field: "avgPrice", headerName: "Average Price (AED)", flex: 1 },
        { field: "lowestPrice", headerName: "Lowest Price (AED)", flex: 1 },
        {
            field: "priceChange",
            headerName: "Price Change (%)",
            flex: 1,
            renderCell: ({ value }) => (
                <div
                    className={`px-5 py-3 badge rounded-pill border ${
                        value > 0 ? "border-danger" : "border-success"
                    }`}
                >
                    <span className="font-weight-bold">
                        {value > 0 ? "+" : ""}
                        {value > 0 ? value.toFixed(2) : value}%
                    </span>
                    <i
                        className={`ml-3 las la-arrow-${
                            value > 0 ? "up" : "down"
                        }`}
                    />
                </div>
            ),
        },
        {
            field: "trend",
            headerName: "Last 90 Days",
            flex: 1,
            renderCell: ({ value }) => <SparklineChart data={value} />,
        },
    ];

    useEffect(() => {
        if (props.categories) {
            setCategories(() => {
                const rows = props.categories.map(
                    (
                        {
                            subcategory,
                            avgPrice,
                            lowestPrice,
                            priceChange,
                            trend,
                        },
                        index
                    ) => {
                        return {
                            id: index + 1, //category.id,
                            subcategory: subcategory.trim(),
                            avgPrice: Math.round(avgPrice),
                            lowestPrice,
                            priceChange: Math.round(priceChange ?? 0),
                            trend,
                        };
                    }
                );
                setFilteredRows(rows);
                return rows;
            });

            setLoading(false);
        }
    }, [props.categories]);

    const handleSearch = (event) => {
        const value = event.target.value.toLowerCase();
        setSearch(value);

        setFilteredRows(() => {
            return categories.filter((row) =>
                row.subcategory.toLowerCase().includes(value)
            );
        });
    };

    return (
        <div className="container">
            <div className="row">
                <div className="col">
                    <div className="card">
                        {loading === true ? (
                            <SpinnerWrapper />
                        ) : (
                            <div className="card-body">
                                <div className="card-title d-flex justify-content-between">
                                    <h5>Materials Trends</h5>
                                    <TextField
                                        label="Search for products..."
                                        variant="outlined"
                                        size="small"
                                        value={search}
                                        onChange={handleSearch}
                                        sx={{
                                            marginBottom: "10px",
                                            float: "right",
                                        }}
                                    />
                                </div>
                                <Box>
                                    <DataGrid
                                        rows={filteredRows}
                                        columns={columns}
                                        pageSize={10}
                                        sx={{
                                            "& .MuiDataGrid-columnHeaders": {
                                                backgroundColor: "#f5f5f5",
                                                color: "#727070",
                                                fontWeight: "bold",
                                            },
                                        }}
                                    />
                                </Box>
                            </div>
                        )}
                        {/* end card-body*/}
                    </div>
                </div>
            </div>
        </div>
    );
}
