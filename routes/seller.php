<?php

use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Seller\SellerRoleController;
use App\Http\Controllers\Seller\SellerStaffController;
use App\Http\Controllers\Seller\StockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\Seller\CatalogController;

//Upload
Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user', 'prevent-back-history'], 'as' => 'seller.'], function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/jstree', 'jstree')->name('categories.jstree');
        Route::get('/jstreeSearch', 'jstreeSearch')->name('categories.jstreeSearch');
    });

    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploads', 'index')->name('uploaded-files.index');
        Route::any('/uploads/create', 'create')->name('uploads.create');
        Route::any('/uploads/file-info', 'file_info')->name('my_uploads.info');
        Route::get('/uploads/destroy/{id}', 'destroy')->name('my_uploads.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
    });
});

Route::group(['namespace' => 'App\Http\Controllers\Seller', 'prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user', 'prevent-back-history'], 'as' => 'seller.'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');

    });

    // Product

    Route::controller(ProductController::class)->group(function () {
        Route::post('/product/temp-store', 'tempStore')->name('product.tempStore');
        Route::get('/product/preview/{slug}', 'preview')->name('product.preview');
        Route::post('/update-price-preview','updatePricePreview')->name('update-price-preview');
        Route::post('/send-checked-attributes','ProductCheckedAttributes')->name('product.checked.attributes');

        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::get('/product/delete_variant', 'delete_variant')->name('products.delete_variant');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::post('/products/store_draft', 'store_draft')->name('products.store_draft');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::get('/getAttributeCategorie', 'getAttributeCategorie')->name('products.getAttributeCategorie');
        Route::get('/getAttributes', 'getAttributes')->name('products.getAttributes');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');
        Route::post('/products/seller/featured', 'updateFeatured')->name('products.featured');
        Route::get('/products/published', 'updatePublished')->name('products.published');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::get('/products/draft/{id}', 'draft')->name('products.draft');
        Route::get('/products/delete_shipping', 'delete_shipping')->name('products.delete_shipping');
        Route::get('/products/delete_image', 'delete_image')->name('products.delete_image');
        Route::get('/products/delete_pricing', 'delete_pricing')->name('products.delete_pricing');
        Route::post('/products/bulk-delete', 'bulk_product_delete')->name('products.bulk-delete');
    });
         // categories



    // Stocks
    Route::controller(StockController::class)->group(function () {
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::post('/save-inventory-record', 'saveRecord')->name('save.inventory.record');
        Route::post('/add-remove-stock', 'storeAddRemoveStock')->name('stock.add_remove');
        Route::post('/inventory/check', 'checkInventory')->name('inventory.check');
        Route::get('/export-stock', 'export')->name('stocks.export');
        Route::get('/stock-operation-report', 'stockOperationReport')->name('stock.operation.report');
        Route::get('/stock-details/search', [StockController::class, 'searchStockDetails'])->name('stock.search');


    }) ;

    // Product Bulk Upload
    Route::controller(ProductBulkUploadController::class)->group(function () {
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::post('/product-bulk-upload/store', 'bulk_upload')->name('bulk_product_upload');
        Route::group(['prefix' => 'bulk-upload/download'], function() {
            Route::get('/category', 'pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'pdf_download_brand')->name('pdf.download_brand');
        });
    });

    // Digital Product
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digitalproducts', 'index')->name('digitalproducts');
        Route::get('/digitalproducts/create', 'create')->name('digitalproducts.create');
        Route::post('/digitalproducts/store', 'store')->name('digitalproducts.store');
        Route::get('/digitalproducts/{id}/edit', 'edit')->name('digitalproducts.edit');
        Route::post('/digitalproducts/update/{product}', 'update')->name('digitalproducts.update');
        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
    });

    //Coupon
    Route::resource('coupon', CouponController::class);
    Route::controller(CouponController::class)->group(function () {
        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');
    });

    //Order
    Route::resource('orders', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/{order_id}', 'invoice_download')->name('invoice.download');
    });
    // Route::get('invoice/{order_id}',[InvoiceController::class, 'invoice_download'])->name('invoice.download');
    //Review
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('reviews');
    });
    // Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

    //Shop
    Route::controller(ShopController::class)->group(function () {
        Route::get('/shop', 'index')->name('shop.index');
        Route::post('/shop/update', 'update')->name('shop.update');
        Route::get('/shop/apply-for-verification', 'verify_form')->name('shop.verify');
        Route::post('/shop/verification_info_store', 'verify_form_store')->name('shop.verify.store');
    });

    //Payments
    Route::resource('payments', PaymentController::class);

    // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });

    // Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/address/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
    });

    // Money Withdraw Requests
    Route::controller(SellerWithdrawRequestController::class)->group(function () {
        Route::get('/money-withdraw-requests', 'index')->name('money_withdraw_requests.index');
        Route::post('/money-withdraw-request/store', 'store')->name('money_withdraw_request.store');
    });

    // Commission History
    Route::controller(CommissionHistoryController::class)->group(function () {
        Route::get('/commission-history', 'index')->name('commission-history.index');
    });

    //Conversations
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations', 'index')->name('conversations.index');
        Route::get('/conversations/show/{id}', 'show')->name('conversations.show');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
        Route::post('conversations/message/store', 'message_store')->name('conversations.message_store');
    });

    // product query (comments) show on seller panel
    Route::controller(ProductQueryController::class)->group(function () {
        Route::get('/product-queries', 'index')->name('product_query.index');
        Route::get('/product-queries/{id}', 'show')->name('product_query.show');
        Route::put('/product-queries/{id}', 'reply')->name('product_query.reply');
    });

    // Support Ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support_ticket', 'index')->name('support_ticket.index');
        Route::post('/support_ticket/store', 'store')->name('support_ticket.store');
        Route::get('/support_ticket/show/{id}', 'show')->name('support_ticket.show');
        Route::post('/support_ticket/reply', 'ticket_reply_store')->name('support_ticket.reply_store');
    });

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/all-notification', 'index')->name('all-notification');
    });

        Route::controller(SellerStaffController::class)->group(function () {
        Route::resource('staffs', SellerStaffController::class);
        Route::get('/staffs/destroy/{id}', [SellerStaffController::class, 'destroy'])->name('staffs.destroy');
    });
    Route::post('vendors/{id}/approve', [SellerController::class,'approve'])->name('staff.approve');



    Route::controller(SellerRoleController::class)->group(function () {
        Route::resource('roles', SellerRoleController::class);
        Route::get('/roles/edit/{id}', 'edit')->name('roles.edit');
        Route::get('/roles/destroy/{id}', 'destroy')->name('roles.destroy');

        // Add Permissiom
        Route::post('/roles/add_permission', 'add_permission')->name('roles.permission');
    });

    //Catalog routes
    Route::controller(CatalogController::class)->group(function () {
        Route::get('/catalog/search_page', 'search')->name('catalog.search_page');
        Route::get('/catalog/search/action', 'search_action')->name('catalog.search.action');
        Route::get('/catalog/search/see_all/{keyword}', 'see_all')->name('catalog.search.see_all');
        Route::get('/catalog/catalog/preview_product/{id}', 'displayPreviewProductInCatalogProduct')->name('catalog.preview_product');
    });
});

