import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import { usePage } from "@inertiajs/react";

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

export function useFormatCurrency() {
    const { defaultCurrency } = usePage().props;

    return (value) => `${value.toFixed(2)} ${defaultCurrency}`;
}

export function formatDate(dateString, locale) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat(locale, {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
}
