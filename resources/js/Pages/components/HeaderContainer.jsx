import { usePage, router } from "@inertiajs/react";
import { route } from "ziggy-js";

export function HeaderContainer() {
    const textPlaceholder =
        "Quod at accusamus nostrum quidem numquam. Atque iusto quos vel rerum nemo. Sequi animi ad odio.";

    const { filter } = usePage().props;

    const handleFilterChange = (newFilter) => {
        router.get(
            route("mawad.index"),
            { filter: newFilter },
            { preserveScroll: true, preserveState: true }
        );
    };

    return (
        <div className="container my-2">
            <div className="row">
                <div className="col-8 d-flex flex-column">
                    <h6>MawadIndex</h6>
                    <p>{textPlaceholder}</p>
                </div>
                <div className="col-4 d-flex justify-content-end">
                    <button
                        className={`my-3 btn btn-primary btn-sm mr-2 ${
                            filter === "avg"
                                ? "bg-orange-500 text-white"
                                : "bg-gray-300"
                        }`}
                        onClick={() => handleFilterChange("avg")}
                    >
                        Avg. Price
                    </button>
                    <button
                        className={`my-3 btn btn-primary btn-sm ${
                            filter === "lowest"
                                ? "bg-gray-500 text-white"
                                : "bg-gray-300"
                        }`}
                        onClick={() => handleFilterChange("lowest")}
                    >
                        Lowest Price
                    </button>
                </div>
            </div>
        </div>
    );
}
