import { Fragment } from "react";

export default function Home() {
    return (
        <Fragment>
            <section className="px-5 pt-5 pb-2">
                <div className="container">
                    <div className="row">
                        <div className="col bg-primary">Cart1</div>
                        <div className="col bg-secondary">Cart2</div>
                        <div className="col bg-danger">Cart3</div>
                    </div>
                </div>
            </section>
        </Fragment>
    );
}
