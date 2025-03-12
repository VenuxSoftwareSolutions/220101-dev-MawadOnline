import { CustomLineChart } from "./CustomLineChart";

export function Cart({ category, subCategory, evolution }) {
    return (
        <div className="card">
            <div className="card-body">
                <div className="d-flex justify-content-between">
                    <div className="d-flex flex-column">
                        <h5 className="card-title">{title}</h5>
                        <h6 className="card-subtitle mb-2 text-muted">
                            {subTitle}
                        </h6>
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
