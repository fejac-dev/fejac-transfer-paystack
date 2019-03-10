@extends('layouts.master')
@section('title', 'Transfers')
@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Transfers</h1>
            <a href="{{ route('transfer.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> New Transfer</a>
        </div>

        <!-- DataTales -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Transfer Details</th>
                                <th>Recipient Account</th>
                                <th>Date</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable({
          "processing": false,
          "serverSide": true,
          "ajax":" {{ route('gettransferlist') }}",
          "columns":[
            {"data": "status", name: "status"},
            { "data": "transaction_details", name: "transaction_details"},
            { "data": "bank_details", name: "bank_details"},
            {"data": "createdAt",
                "render": function (data) {
                    var date = new Date(data);
                    var month = date.getMonth() + 1;
                    return date.getFullYear() + "/" +  (month.length > 1 ? month : "0" + month) + "/" + date.getDate() ;
                },
                "name": "createdAt"
            },
            {"data": "action"},
          ]
        });
    });
    function retryPayment(TransferCode){
        swal("To be implemented");
    }
</script>
@endsection
