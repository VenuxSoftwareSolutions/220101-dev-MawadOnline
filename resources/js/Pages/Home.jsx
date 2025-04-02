import { HeaderContainer } from "./components/HeaderContainer";
import { MaterialsTrendsContainer } from "./components/MaterialsTrendsContainer";
import { CartsWrapper } from "./components/CartsWrapper";
import { HistoricalGraphWrapper } from "./components/HistoricalGraphWrapper";
import { ErrorBoundary } from "./components/ErrorBoundary";

export default function Home() {
    return (
        <div
            className="px-5 pt-5 pb-2"
            style={{ backgroundColor: "#e0e4eafc" }}
        >
            <section className="px-5 pb-2">
                <ErrorBoundary>
                    <HeaderContainer />
                </ErrorBoundary>

                <ErrorBoundary>
                    <CartsWrapper />
                </ErrorBoundary>
                <ErrorBoundary>
                    <HistoricalGraphWrapper />
                </ErrorBoundary>
                <ErrorBoundary>
                    <MaterialsTrendsContainer />
                </ErrorBoundary>
            </section>
        </div>
    );
}
