import { useState, useEffect } from "react";
import { usePage } from "@inertiajs/react";

import { handleFilterChange as handleFilterChangeHelper } from "../helper.js";

export function HeaderContainer() {
    const textPlaceholder = __(
        "Your go-to tool for monitoring daily, weekly, and long-term changes in material costs. Compare rates, view historical data, and identify market trends with clear graphs and insights."
    );

    const { filter } = usePage().props;

    const { filter: filterFromQueryString } = Object.fromEntries(
        new URLSearchParams(window.location.search)
    );

    const [localFilter, setLocalFilter] = useState(filter);

    const handleFilterChange = (newFilter) => {
        handleFilterChangeHelper({ filter: newFilter });
    };

    useEffect(() => {
        if (filterFromQueryString !== undefined) {
            setLocalFilter(filterFromQueryString);
        }
    }, [filterFromQueryString]);

    return (
        <div className="container my-2">
            <div className="row">
                <div className="col-8 d-flex flex-column">
                    <h4>MawadIndex</h4>
                    <p>{textPlaceholder}</p>
                </div>
                <div className="my-3 col-4 d-flex justify-content-end">
                    <button
                        className={`my-3 btn btn-primary btn-sm mr-2 ${
                            localFilter !== undefined && localFilter === "avg"
                                ? "text-white"
                                : "text-secondary bg-transparent border-secondary"
                        }`}
                        style={{
                            backgroundColor:
                                localFilter !== undefined &&
                                localFilter === "avg"
                                    ? "var(--primary)"
                                    : "#e4aa8f",
                        }}
                        onClick={() => handleFilterChange("avg")}
                    >
                        {__("Average Price")}
                    </button>
                    <button
                        className={`my-3 btn btn-primary btn-sm mr-2 ${
                            localFilter !== undefined &&
                            localFilter === "lowest"
                                ? "text-white"
                                : "text-secondary bg-transparent border-secondary"
                        }`}
                        style={{
                            backgroundColor:
                                localFilter !== undefined &&
                                localFilter === "lowest"
                                    ? "var(--primary)"
                                    : "#e4aa8f",
                        }}
                        onClick={() => handleFilterChange("lowest")}
                    >
                        {__("Lowest Price")}
                    </button>
                </div>
            </div>
        </div>
    );
}
