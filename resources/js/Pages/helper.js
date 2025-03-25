import { router } from "@inertiajs/react";
import { route } from "ziggy-js";

export function handleFilterChange(newParams) {
    const currentParams = Object.fromEntries(
        new URLSearchParams(window.location.search)
    );

    router.get(
        route("mawad.index"),
        {
            ...currentParams,
            ...newParams,
        },
        { preserveScroll: true, preserveState: true }
    );
}
