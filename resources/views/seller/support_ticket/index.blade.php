@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Support Ticket') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mx-auto mb-3" >
            <div  id="step2" class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition" data-toggle="modal" data-target="#ticket_modal">
                <span class="size-70px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                    <i class="las la-plus la-3x text-white"></i>
                </span>
                <div class="fs-20 text-primary">{{ translate('Create a Ticket') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Tickets')}}</h5>
        </div>
          <div class="card-body">
              <table  id="step1" class="table aiz-table mb-0">
                  <thead>
                      <tr>
                          <th data-breakpoints="lg">{{ translate('Ticket ID') }}</th>
                          <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                          <th>{{ translate('Subject')}}</th>
                          <th>{{ translate('Status')}}</th>
                          <th data-breakpoints="lg">{{ translate('Options')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($tickets as $key => $ticket)
                          <tr>
                              <td>#{{ $ticket->code }}</td>
                              <td>{{ $ticket->created_at }}</td>
                              <td>{{ $ticket->subject }}</td>
                              <td>
                                  @if ($ticket->status == 'pending')
                                      <span class="badge badge-inline badge-danger">{{ translate('Pending')}}</span>
                                  @elseif ($ticket->status == 'open')
                                      <span class="badge badge-inline badge-secondary">{{ translate('Open')}}</span>
                                  @else
                                      <span class="badge badge-inline badge-success">{{ translate('Solved')}}</span>
                                  @endif
                              </td>
                              <td>
                                  <a href="{{route('seller.support_ticket.show', encrypt($ticket->id))}}" class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none">
                                      {{ translate('View Details')}}
                                      <i class="la la-angle-right text-sm"></i>
                                  </a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
              <div class="aiz-pagination">
                  {{ $tickets->links() }}
              </div>
          </div>
    </div>
@endsection

@section('modal')
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
                  <form class="" action="{{ route('seller.support_ticket.store') }}" method="post" enctype="multipart/form-data">
                      @csrf
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
@endsection

@section('script')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
    let tour = introJs();
    let step_number = 0 ;
    tour.setOptions({
        steps: [
            {
                element: document.querySelector('#dashboard'),
                title: 'Dashboard',
                intro: "Welcome to your e-Shop dashboard! This is your central hub for managing your shop's performance, sales, and settings.",
                position: 'right'
            },
            {
                element: document.querySelector('#products'),
                title: 'Step 1: Manage Products',
                intro: "Here, you can add, edit, and manage your products. Showcase your offerings to attract buyers and keep your inventory up to date.",
                position: 'right'
            },
            {
                element: document.querySelector('#reviews'),
                title: 'Step 2: Monitor Reviews',
                intro: "Stay informed about what customers are saying. Manage and respond to reviews to maintain a positive reputation and improve your products.",
                position: 'right'
            },
            {
                element: document.querySelector('#catalog'),
                title: 'Step 3: Catalog Management',
                intro: "Organize your products into categories and collections. Enhance discoverability and make it easier for customers to find what they're looking for.",
                position: 'right'
            },
            {
                element: document.querySelector('#stock'),
                title: 'Step 4: Stock Management',
                intro: "Track your inventory levels and manage stock efficiently. Avoid overselling and keep your customers satisfied with accurate stock updates.",
                position: 'right'
            },
            {
                element: document.querySelector('#stock_details'),
                title: 'Step 5: Stock Details',
                intro: "View detailed information about your stock, including quantities, variations, and restocking options. Keep your inventory organized and up to date.",
                position: 'right'
            },
            {
                element: document.querySelector('#order'),
                title: 'Step 6: Order Management',
                intro: "Keep track of incoming orders, process payments, and manage order fulfillment. Ensure smooth transactions and timely delivery to your customers.",
                position: 'right'
            },
            {
                element: document.querySelector('#packages'),
                title: 'Step 7: Package Management',
                intro: "Manage packaging options and shipping details for your products. Choose the best packaging solutions to protect your items during transit.",
                position: 'right'
            },
            {
                element: document.querySelector('#package_list'),
                title: 'Step 8: Package List',
                intro: "View a list of all packages associated with your orders. Keep track of shipments and delivery status to provide accurate updates to customers.",
                position: 'right'
            },
            {
                element: document.querySelector('#staff'),
                title: 'Step 9: Staff Management',
                intro: "Add, remove, or manage staff members who assist with running your shop. Delegate tasks and collaborate effectively to streamline operations.",
                position: 'right'
            },
            {
                element: document.querySelector('#lease'),
                title: 'Step 10: Lease Management',
                intro: "Manage lease agreements for your shop premises or equipment. Stay organized and ensure compliance with lease terms and conditions.",
                position: 'right'
            },
            {
                element: document.querySelector('#lease_details'),
                title: 'Step 11: Lease Details',
                intro: "View detailed information about your lease agreements, including terms, renewal dates, and rental payments. Stay on top of lease obligations.",
                position: 'right'
            },
            {
                element: document.querySelector('#support_tickets'),
                title: 'Step 12: Support Tickets',
                intro: "Handle customer inquiries, feedback, and support requests. Provide timely assistance and resolve issues to maintain customer satisfaction.",
                position: 'right'
            },
            {
                element: document.querySelector('#setting'),
                title: 'Step 13: Account Settings',
                intro: "Adjust your account settings and preferences. Customize your dashboard experience to suit your needs and optimize your workflow.",
                position: 'right'
            }
        ],

        doneLabel: 'Next', // Replace the "Done" button with "Next"
        exitOnEsc : false ,
        exitOnOverlayClick : false ,
        disableInteraction : true ,
        overlayOpacity : 0.4 ,
        showStepNumbers : true ,
        hidePrev : true ,
        showProgress :true ,
    });

        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.shop.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(13);
    });
</script>
@endsection
