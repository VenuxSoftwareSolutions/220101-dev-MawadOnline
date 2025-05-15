export function SpinnerWrapper() {
    return (
        <div className="m-5 d-flex justify-content-center">
            <div className="spinner-grow text-info mx-1" role="status">
                <span className="sr-only">Loading...</span>
            </div>
            <div className="spinner-grow text-danger mx-1" role="status">
                <span className="sr-only">Loading...</span>
            </div>
            <div className="spinner-grow text-warning mx-1" role="status">
                <span className="sr-only">Loading...</span>
            </div>
        </div>
    );
}
