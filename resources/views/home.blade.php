@extends('layouts.master')
@section('title', 'Dashboard')
@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Available Balance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{  $currentBalance['currency'] . ' ' . ($currentBalance['balance']/100) }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Supplier</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($transferRecipient)}}</div>
                    </div>
                    <div class="col-auto">
                      {{-- <i class="fas fa-dollar-sign fa-2x text-gray-300"></i> --}}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transfer Attempts</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transferCount }}</div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
                </div>
            </div>
            </div>

            <!-- Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Successful Transfers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $successfulTransfer }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->



          <!-- Content Row -->
          <div class="row ">
                <div class="col-xl-12 col-md-12 mb-4">
                <!-- DataTales -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"></h6>
                        <a href="{{ route('transferrecipient.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-add fa-sm text-white-50"></i>Create Transfers</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Account Name</th>
                                    <th>Bank Code</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
@endsection
@section('scripts')

<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
        var table;
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
             table =  $('#dataTable').DataTable({
              "processing": false,
              "serverSide": true,
              "ajax":" {{ route('gettransferrecipientlist') }}",
              "columns":[
                {"data": "id", name: "id"},
                { "data": "name", name: "name"},
                { "data": "details.bank_name", name: "details.bank_name"},
                { "data": "details.account_number", name: "details.account_number"},
                {"data": "details.account_name", name: "details.account_name"},
                {"data": "details.bank_code", name: "details.bank_code"},
              ]
            });
        });
    </script>
@endsection
