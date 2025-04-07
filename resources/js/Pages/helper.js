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
    let dateParts = dateString.split("-");
    if (dateParts.length === 2) {
        dateString += "-01";
    }

    const date = new Date(dateString);

    let _locale = locale === "en" ? `${locale}-GB` : locale;

    return new Intl.DateTimeFormat(_locale, {
        day: dateParts.length === 3 ? "numeric" : undefined,
        month: "short",
        year: "numeric",
    }).format(date);
}

export function calculateSlidePerView(dataLength) {
    return dataLength > 2 ? Math.min(dataLength / 2 + 0.5, 2.5) : 1.5;
}

export function capitalizeFirstLetter(string) {
    if (!string) return "";
    return string.charAt(0).toUpperCase() + string.slice(1);
}
