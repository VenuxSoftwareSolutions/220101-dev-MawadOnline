<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title strong-600 heading-5">{{ translate('Create a Ticket')}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body px-3 pt-3">
                  <form class="" action="{{ route('support_ticket.save_ticket') }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <input type="hidden" id="order_details_id" name="order_details" value="" />
                      <div class="row">
                          <div class="col-md-2">
                              <label>{{ translate('Subject')}}</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="form-control mb-3" placeholder="{{ translate('Subject')}}" name="subject" required>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label>{{ translate('Provide a detailed description')}}</label>
                          </div>
                          <div class="col-md-10">
                              <textarea type="text" class="form-control mb-3" rows="3" name="details" placeholder="{{ translate('Type your reply')}}" data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo" required></textarea>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                          <div class="col-md-10">
                              <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                  <div class="input-group-prepend">
                                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                  </div>
                                  <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                  <input type="hidden" name="attachments" class="selected-files">
                              </div>
                              <div class="file-preview box sm">
                              </div>
                          </div>
                      </div>
                      <div class="text-right mt-4">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('cancel')}}</button>
                          <button type="submit" class="btn btn-primary">{{ translate('Send Ticket')}}</button>
                      </div>
                  </form>
              </div>
            </div>
        </div>
</div>
