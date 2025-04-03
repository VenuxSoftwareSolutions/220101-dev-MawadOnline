import { Fragment } from "react";
import { usePage } from "@inertiajs/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";

import { Cart } from "./Cart";
import { NoDataCart } from "./NoDataCart";

import "swiper/css";
import "swiper/css/navigation";

import "./slider.css";

export function CartsWrapper() {
    const { top10CategoriesEvolution } = usePage().props;

    const slidesPerView =
        top10CategoriesEvolution.length > 2
            ? top10CategoriesEvolution.length / 2
            : 1.5;

    return (
        <div className="container my-2">
            <div className="row chart-slider-container">
                {top10CategoriesEvolution.length === 0 ? (
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
                )}
                {top10CategoriesEvolution.length > 0 ? (
                    <Fragment>
                        <button className="custom-prev" />
                        <button className="custom-next" />
                    </Fragment>
                ) : null}
            </div>
        </div>
    );
}
