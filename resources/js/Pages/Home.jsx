import { HeaderContainer } from "./components/HeaderContainer";
import { MaterialsTrendsContainer } from "./components/MaterialsTrendsContainer";
import { CartsWrapper } from "./components/CartsWrapper";

export default function Home() {
    return (
        <div
            className="px-5 pt-5 pb-2"
            style={{ backgroundColor: "#e0e4eafc" }}
        >
            <section className="px-5 pt-5 pb-2">
                <HeaderContainer />
                <CartsWrapper />
                <div className="container">
                    <div className="row">
                        <div className="col">Coming soon</div>
                    </div>
                </div>
                <MaterialsTrendsContainer />
            </section>
        </div>
    );
}
