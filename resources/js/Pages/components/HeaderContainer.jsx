export default function HeaderContainer() {
    const textPlaceholder =
        "Quod at accusamus nostrum quidem numquam. Atque iusto quos vel rerum nemo. Sequi animi ad odio.";

    return (
        <div className="container my-2">
            <div className="row">
                <div className="col-8 d-flex flex-column">
                    <h6>MawadIndex</h6>
                    <p>{textPlaceholder}</p>
                </div>
                <div className="col-4 d-flex justify-content-end">
                    <button className="my-3 btn btn-primary btn-sm mr-2">
                        Avg. Price
                    </button>
                    <button className="my-3 btn btn-primary btn-sm">
                        Lowest Price
                    </button>
                </div>
            </div>
        </div>
    );
}
