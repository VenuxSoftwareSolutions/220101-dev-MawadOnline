function updateUrl(param, value) {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    if (value.length > 0) {
        params.set(param, value);
    } else {
        params.delete(param);
    }

    url.search = params.toString();
    location.href = url.toString();
}
