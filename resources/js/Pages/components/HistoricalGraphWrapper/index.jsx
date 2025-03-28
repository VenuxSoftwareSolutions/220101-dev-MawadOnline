import { useEffect, useState } from "react";
import { usePage } from "@inertiajs/react";

import { MemoisedLineChartWrapper } from "./LineChartWrapper.jsx";
import { CategoriesSelectFilter } from "./CategoriesSelectFilter.jsx";
import { PeriodsButtonsFilter } from "./PeriodsButtonsFilter.jsx";

import { handleFilterChange } from "../../helper.js";

export function HistoricalGraphWrapper() {
    const [isMounted, setIsMounted] = useState(false);

    const { categoryPrices } = usePage().props;

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
                                    <CategoriesSelectFilter />
                                </div>
                                <div className="col d-flex align-items-center justify-content-end">
                                    <PeriodsButtonsFilter />
                                </div>
                            </div>

                            <MemoisedLineChartWrapper data={categoryPrices} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
