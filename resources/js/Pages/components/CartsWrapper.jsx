import { usePage } from "@inertiajs/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";

import { Cart } from "./Cart";

import "swiper/css";
import "swiper/css/navigation";

import "./slider.css";

export function CartsWrapper() {
    const {
        props: { top10CategoriesEvolution },
    } = usePage();

    const slidesPerView = 2.5;

    return (
        <div className="container my-2">
            <div className="row chart-slider-container">
                <Swiper
                    modules={[Navigation]}
                    spaceBetween={2}
                    slidesPerView={slidesPerView}
                    navigation={{
                        nextEl: ".custom-next",
                        prevEl: ".custom-prev",
                    }}
                >
                    {top10CategoriesEvolution.map(
                        (
                            {
                                category,
                                parentCategory,
                                evolution,
                                priceChange,
                            },
                            index
                        ) => (
                            <SwiperSlide
                                key={`${category}_${parentCategory}-${index}`}
                            >
                                <div className="col">
                                    <Cart
                                        category={category}
                                        subCategory={parentCategory}
                                        evolution={evolution}
                                        priceChange={priceChange}
                                    />
                                </div>
                            </SwiperSlide>
                        )
                    )}
                </Swiper>
                <button className="custom-prev" />
                <button className="custom-next" />
            </div>
        </div>
    );
}
