import { CustomLineChart } from "./CustomLineChart";

export function Cart({ category, subCategory, evolution, priceChange }) {
    return (
        <div className="card">
            <div className="card-body">
                <div className="d-flex justify-content-between">
                    <div className="d-flex flex-column">
                        <small className="card-subtitle mb-2 text-muted">
                            Main Category / {subCategory}
                        </small>

                        <h5 className="card-title">{category}</h5>
                    </div>
                    <button className="my-3 btn btn-primary btn-xs">
                        Buy product
                    </button>
                </div>
                <hr />
                <div
                    className="card-text d-flex justify-content-between"
                    style={{ gap: "50px" }}
                >
                    <CustomLineChart data={evolution} />
                    <div className="d-flex flex-column align-items-center justify-content-center">
                        <div
                            className={`d-flex justify-content-center align-items-center p-1 border border-${
                                priceChange.percentage <= 0
                                    ? "success alert alert-success"
                                    : "danger alert alert-danger"
                            }`}
                        >
                            <span
                                className={`text-${
                                    priceChange.absolute <= 0
                                        ? "success"
                                        : "danger"
                                } font-weight-bold`}
                            >
                                {priceChange.percentage}%
                            </span>
                            {priceChange.percentage !== 0 ? (
                                <i
                                    className={`las la-arrow-${
                                        priceChange.percentage > 0
                                            ? "up"
                                            : "down"
                                    }`}
                                    style={{
                                        transform: `rotate(${
                                            priceChange.percentage > 0
                                                ? ""
                                                : "-"
                                        }45deg)`,
                                    }}
                                />
                            ) : null}
                        </div>
                        <div
                            className={`font-weight-bold px-4 py-3 text-${
                                priceChange.absolute <= 0 ? "success" : "danger"
                            }`}
                        >
                            {priceChange.absolute > 0
                                ? `+${priceChange.absolute}`
                                : priceChange.absolute === 0
                                ? priceChange.absolute
                                : `-${priceChange.absolute}`}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
