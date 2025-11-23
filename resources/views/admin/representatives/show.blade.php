@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ 'employee_details' }}
@endsection

@section('page_name')
    {{ 'employee_details' }}
@endsection
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printable,
            .printable * {
                visibility: visible;
            }

            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-btn {
                visibility: hidden;
            }
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <a class="btn btn-success text-white addBtn">
                                <span>
                                    <i class="fe fe-arrow-left"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="wideget-user text-center">
                                <div class="wideget-user-desc">
                                    {{-- <div class="wideget-user-img">
                                        <img class="" src="{{ getFile($obj->image) }}" alt="img">
                                    </div> --}}
                                    <div class="user-wrap">
                                        <h4 class="mb-1 text-capitalize">
                                        </h4>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted mb-4 w-100">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card bg-info img-card box-success-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h2 class="mb-0 number-font"></h2>
                                                <p class="text-white mb-0">الطلبات</p>
                                            </div>
                                            <div class="mr-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-3 d-flex justify-content-end">
                <div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="wideget-user-tab">
                <div class="tab-menu-heading">
                    <div class="tabs-menu1">
                        <ul class="nav">
                            <li class=""><a href="#tab-1" class="tab-action active show"
                                    data-toggle="tab">الطلبات</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content">


            <div class="tab-pane  active show" id="tab-1">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">
                            <div class="table-responsive">
                                {{-- @if ($obj->count() > 0) --}}
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>المستخدم</td>
                                            <td>العنوان</td>
                                            <td>مقدم الخدمة </td>
                                            <td>ملحوظة</td>
                                            <td>الحالة</td>
                                            <td>اجمالى السعر </td>
                                            <td>التصنيف</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($orderRepresentatives->count() > 0)
                                            @foreach ($orderRepresentatives as $orderRepresentative)
                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->user->name }}</td>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->address->address }}</td>
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->provider->name }}</td>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->note ?? '.........' }}</td>

                                                    <td class="text-capitalize">{{ $orderRepresentative->order->status }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->total_price }} </td>
                                                    <td class="text-capitalize">
                                                        {{ $orderRepresentative->order->category->title_ar }}</td>
                                                    <td class="text-capitalize"></td>
                                                    <td class="text-capitalize"></td>
                                                </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h4>{{ trans('no_data') }} </h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab-2">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>

                                        <tr>
                                            <td>{{ 'lawyer' }}</td>
                                            <td>{{ 'price' }}</td>
                                            <td>{{ 'status' }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-capitalize">
                                            </td>
                                            <td class="text-capitalize"></td>
                                            <td class="text-capitalize"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4>{{ 'no_data' }} </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="tab-pane" id="tab-3">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>{{ 'date' }}</td>
                                            <td>{{ 'details' }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($obj) --}}
                                        <tr>
                                            <td class="text-capitalize"></td>
                                            <td class="text-capitalize">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- @else --}}
                                <h4>{{ 'no_data' }} </h4>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="tab-pane" id="tab-4">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>title</td>
                                            <td>from_user</td>
                                            <td>to_user</td>
                                            <td>court_case_event_status</td>
                                            <td>court_case_event_price</td>
                                            <td>date</td>
                                            <td>paid</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($obj) --}}
                                        <tr>
                                            <td class="text-capitalize"></td>
                                            <td class="text-capitalize">

                                            </td>
                                            <td class="text-capitalize">
                                            </td>
                                            <td class="text-capitalize">
                                            <td class="text-capitalize">
                                            <td class="text-capitalize">
                                            </td>
                                            <td class="text-capitalize"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- @else --}}
                                <h4>{{ 'no_data' }} </h4>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- COL-END -->
    </div>


    <!-- Create Or Edit Modal -->
    <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">{{ 'object_details' }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Create Or Edit Modal -->

    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function() {
            $(' .table-bordered').DataTable();
        });
    </script>


    <script>
        $('.tab-action').on('click', function(e) {
            $('.tab-action').removeClass('active show');
            $('.tab-pane').removeClass('active show');

            $(this).addClass('active show');
            let href = $(this).attr('href');
            $(`${href}`).addClass('active show');
        });


        function printDiv(divId) {
            var divContent = document.getElementById(divId).innerHTML;
            var printWindow = window.open('', '_blank', 'height=1200,width=1200');

            // Start writing the HTML structure
            printWindow.document.write('<html><head><title>Print</title>');

            // Copy all stylesheets from the original document to the new window
            Array.prototype.forEach.call(document.querySelectorAll("link[rel='stylesheet'], style"), function(link) {
                printWindow.document.write(link.outerHTML);
            });

            // Write internal styles specifically for printing
            printWindow.document.write(
                '<style>@media print { body * { visibility: hidden; } .printable, .printable * { visibility: visible; } .printable { position: absolute; left: 0; top: 0; width: 100%; }} .print-btn{ display: none; }</style>'
            );

            // Close the head tag and start the body
            printWindow.document.write('</head><body>');

            // Add the content to be printed
            printWindow.document.write('<div class="printable">' + divContent + '</div>');

            // Close the HTML structure
            printWindow.document.write('</body></html>');

            // Close the document to complete the writing process
            printWindow.document.close();

            // Ensure the styles and scripts are loaded before printing
            printWindow.onload = function() {
                // Delay printing to ensure styles are applied
                setTimeout(function() {
                    printWindow.focus(); // Focus the new window
                    printWindow.print(); // Trigger the print dialog
                    printWindow.close(); // Close the new window after printing
                }, 250); // Adjust delay as necessary
            };
        }

        editScript();


        function showIncentiveModal(routeOfEdit) {
            $(document).on('click', '.incentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        function showGetIncentiveModal(routeOfEdit) {
            $(document).on('click', '.getIncentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }
    </script>
@endsection
