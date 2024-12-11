<div id="checkout-timeout-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{ translate('Alert') }}</h4>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">{{ translate('Checkout session timeout!') }}</p>
                <a href="javascript:void(0)" onclick="redirectToCart()"
                    class="btn btn-primary mt-2">{{ translate("Back to cart") }}</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $("#checkout-timeout-modal").modal("show");
    });

    function redirectToCart() {
        location.href = "/cart";
    }
</script>
