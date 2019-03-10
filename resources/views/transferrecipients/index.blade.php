@extends('layouts.master')
@section('title', 'Transfer Recipients')
@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Transfer Recipients - Suppliers</h1>
            <a href="{{ route('transferrecipient.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Create Supplier</a>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Bank Code</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer Modal-->
    <div class="modal fade" id="theTransfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tranfer Fund to Supplier</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row pb-4 justify-content-center align-items-center align-middle">
                                        <div class="">
                                            <div class="text-ellipsis text-mid text-center"><b class="">Transfer to <span id="transferName"></span> - <span id="transferBank"></span> - <span id="transferAcctNum"></span></b></div>
                                            <div class="text-primary text-center"><b class=""><span id="transferAmt"></span></b></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="transferFormDiv" class="starttransfer">
                                    <form id="transferForm"  class="form-validation">
                                        <input type="hidden" name="recipient" id="theRecipient" />
                                        <input type="hidden" name="source" value="balance" />
                                        <div class="row pb-4">
                                            <label class="col-sm-4 control-label">Choose Balance</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="theBalance" name="currency">
                                                    <option value="NGN"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row pb-4">
                                                <label class="col-sm-4 control-label">Amount to send</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" min="100" placeholder="Amount (NGN)" currency-mask="" id="theTransferAmount" name="amount" required="true">
                                                </div>
                                            </div>
                                            <div class="row pb-4">
                                                <label class="col-sm-4 control-label">Transfer Note</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="reason" placeholder="Optional">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pb-4">
                                            <label class="col-sm-4 control-label">Transfer Reference</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" onkeyup="return forceLower(this);" name="reference" placeholder="Optional" >
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="confirmotp" style="display: none" id="confirmTransfer">
                                    <form id="transferOTPForm" class="">
                                        <div class="clear m-b-md">
                                            <div class="text-center text-muted"><i class="icon-layers"></i></div>
                                        </div>
                                        <p class="m-b-lg text-center padder text-mid" id="ngdialog2-aria-describedby">
                                            A confirmation code has sent to your phone number. Please enter the code here to complete this transfer
                                        </p>

                                        <div class="row pb-4">
                                            <div class="col-sm-4 text-right">
                                                <label class="control-label">Enter Code</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input name="otp" type="text" class="form-control" id="confirmOTP" placeholder="Confirmation code" required="">
                                                <div class="m-t-xs">
                                                    <a class="text-primary text-sm" href="javascipt::;" onclick="resendCode()"><i class="icon-reload"></i> Resend code</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" onclick="window.location.reload();" data-dismiss="modal">Cancel</button>
                                <a class="btn btn-primary starttransfer" onclick="processTransfer();" href="javascript::;">Start Transfer</a>
                                <a class="btn btn-primary confirmotp" style="display:none;" onclick="processOTPTransfer();" href="javascript::;">Finalize Transfer</a>
                            </div>
                        </div>
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
    var table;
    var theTransferAction = 1; //Transfer Stage
    var theCurrentBalance = 0; //Default Balanace
    var theActiveRecipient, theActiveTransferCode;

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
            {"data": "action"},
          ]
        });
    });


    function processTransfer(){
        if (validateTransfer()){
            $("#theTransferAmount").val($("#theTransferAmount").val() * 100);
            var theData = $("#transferForm").serialize();
            theData = theData + "&_token=" + $('meta[name=csrf-token]').attr('content');

            $.post('{{route("transfer.store")}}', theData)
            .done(function (data) {
                if (data.status){
                    if (data.message =='Transfer requires OTP to continue' ){
                        $(".starttransfer").hide(1000);
                        $(".confirmotp").show(1200);
                        theActiveTransferCode = data.data.transfer_code;
                    }
                }
            })
            .fail(function () {
                alert("error");
            });
        }

    }
    function forceLower(strInput){
        strInput.value = strInput.value.toLowerCase();
    }
    function resendCode(){
        var theData =  "transfer_code=" + theActiveTransferCode  + "&_token=" + $('meta[name=csrf-token]').attr('content');
        $.post('{{route("transfer.resendotp")}}', theData)
        .done(function (data) {
            if (data.status && data.message == 'OTP has been resent'){
                swal("otp has been resent");
            }else{
                swal(data.message);
            }
        })
        .fail(function () {
            swal("error");
        });
    }
    function processOTPTransfer(){
        if ($("#confirmOTP").val() !=""){
            var theData =  "transfer_code=" + theActiveTransferCode + "&otp=" +   $("#confirmOTP").val() + "&_token=" + $('meta[name=csrf-token]').attr('content');
            $.post('{{route("transfer.confirmotp")}}', theData)
            .done(function (data) {
                if (data.status){
                    swal("Done", "Your transfer was successful", "success");
                    window.location.reload();
                }
                alert(data.status);
            })
            .fail(function () {
                alert("error");
            });
        }else{
            swal("Enter otp to continue");
        }

    }
    $('#dataTable').on('click', 'tbody .transfer_btn', function () {
        var data_row = table.row($(this).closest('tr')).data();
        $.get("{{ route('getbalance') }}", function(data, status){
            theTransferAction = 1
            theCurrentBalance = data.balance  / 100;
            var cboBalance = $("#theBalance");
            cboBalance.empty();
            cboBalance.append($("<option />").val(data.currency).text( data.currency +  " - "  + theCurrentBalance));
            $("#theTransfer").modal('show');
                $('#theTransfer').on('shown.bs.modal', function() {
                    $('#theRecipient').val(data_row.recipient_code);
                    $('#transferName').html(data_row.name);
                    $('#transferAcctNum').html(data_row.details.account_number);
                    $('#transferBank').html(data_row.details.bank_name);
            });
          });
    })

    function validateTransfer(){
        let isValid = false;
        let transferAmount = $("#theTransferAmount").val();
        if ( transferAmount ==""){
            swal("Please enter an amount and it must be greater than NGN 100");
            return false
        }
        transferAmount = parseFloat(transferAmount);
        if (transferAmount < 100){
            swal("Minimum transfer amount is NGN 100");
            return false
        }
        if ((transferAmount  + 50) > theCurrentBalance){
            swal("Transfer amount + charges (NGN 50) should be less than your balance");
            return false
        }
        return true
    }
</script>
@endsection
