import { CustomLineChart } from "./CustomLineChart";

export function Cart({ category, subCategory, evolution }) {
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
                <div className="card-text">
                    <CustomLineChart data={evolution} />
                </div>
            </div>
        </div>
    );
}
