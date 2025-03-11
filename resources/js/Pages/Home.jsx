import { usePage } from "@inertiajs/react";

import Cart from "./components/Cart";
import HeaderContainer from "./components/HeaderContainer";
import MaterialsTrendsContainer from "./components/MaterialsTrendsContainer";

export default function Home() {
    const {
        props: { categories, top10Categories },
    } = usePage();

    return (
        <div
            className="px-5 pt-5 pb-2"
            style={{ backgroundColor: "#e0e4eafc" }}
        >
            <section className="px-5 pt-5 pb-2">
                <HeaderContainer />
                <div className="container my-2">
                    <div className="row">
                        {top10Categories.map(
                            (
                                { category, parentCategory, evolution },
                                index
                            ) => (
                                <div
                                    key={`${category}_${parentCategory}-${index}`}
                                    className="col"
                                >
                                    <Cart
                                        title={category}
                                        subTitle={parentCategory}
                                        evolution={evolution}
                                    />
                                </div>
                            )
                        )}
                    </div>
                </div>
                <div className="container">
                    <div className="row">
                        <div className="col">Coming soon</div>
                    </div>
                </div>
                <MaterialsTrendsContainer categories={categories} />
            </section>
        </div>
    );
}
