import { Fragment } from "react";
import { usePage } from "@inertiajs/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";

import { calculateSlidePerView } from "../helper";

import { Cart } from "./Cart";
import { NoDataCart } from "./NoDataCart";

import "swiper/css";
import "swiper/css/navigation";

import "./slider.css";

export function CartsWrapper() {
    const { selectedCategoriesEvolution } = usePage().props;

    const slidesPerView = calculateSlidePerView(
        selectedCategoriesEvolution.length
    );

    return (
        <div className="container my-2">
            <div className="row chart-slider-container">
                {selectedCategoriesEvolution.length === 0 ? (
                    <div className="col">
                        <NoDataCart />
                    </div>
                ) : (
                    <Swiper
                        modules={[Navigation]}
                        spaceBetween={2}
                        slidesPerView={slidesPerView}
                        navigation={{
                            nextEl: ".custom-next",
                            prevEl: ".custom-prev",
                        }}
                    >
                        {selectedCategoriesEvolution.map(
                            (
                                {
                                    category,
                                    parentCategory,
                                    mainCategory,
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
                                            mainCategory={mainCategory}
                                            evolution={evolution}
                                            priceChange={priceChange}
                                        />
                                    </div>
                                </SwiperSlide>
                            )
                        )}
                    </Swiper>
                )}
                {selectedCategoriesEvolution.length > 0 ? (
                    <Fragment>
                        <button className="custom-prev" />
                        <button className="custom-next" />
                    </Fragment>
                ) : null}
            </div>
        </div>
    );
}
