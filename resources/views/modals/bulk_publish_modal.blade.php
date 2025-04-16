<div id="bulk-publish-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1 modal-message"></p>
                <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <a href="javascript:void(0)" onclick="bulk_publish(true)" class="d-none btn btn-primary mt-2 action-btn">{{ __("Publish") }}</a>

                <a href="javascript:void(0)" onclick="bulk_publish(false)" class="d-none btn btn-primary mt-2 action-btn">{{ __("Unpublish") }}</a>
            </div>
        </div>
    </div>
</div>
