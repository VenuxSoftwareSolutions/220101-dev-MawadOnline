import { usePage } from "@inertiajs/react";

import { handleFilterChange } from "../../helper";

export function CategoriesSelectFilter() {
    const { selectedCategories } = usePage().props;

    const { category_id } = Object.fromEntries(
        new URLSearchParams(window.location.search)
    );

    const handleCategoryIdFilterChange = (newCategoryIdFilter) => {
        handleFilterChange({ category_id: newCategoryIdFilter });
    };

    return (
        <select
            className="form-control"
            name="category_id"
            value={
                category_id !== undefined
                    ? category_id
                    : selectedCategories[0].id
            }
            onChange={(e) => handleCategoryIdFilterChange(e.target.value)}
        >
            {selectedCategories.map(({ id, name }) => (
                <option key={id} value={id}>
                    {name}
                </option>
            ))}
        </select>
    );
}
