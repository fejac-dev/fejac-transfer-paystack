@extends('layouts.master')
@section('title', 'Trips')
@section('styles')
@endsection
@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 col-lg-6 center-block">
                                {!!Form::open()->post()->route('transferrecipient.store')->id('transferrecipient-create')!!}

                                <div class="" >
                                    <div class="tab-content ">
                                        <div class="" id="trip_details">
                                            <div class="row m-0">
                                                <div class="col-md-6">
                                                    {!!Form::text('name', 'Supplier Name')!!}
                                                </div>
                                                <div class="col-md-6">
                                                    {!!Form::text('description', 'Description')!!}
                                                </div>
                                            </div>
                                            <div class="row m-0">
                                                <div class="col-md-6">
                                                    <label>Recipient Bank*</label>
                                                    <select class="form-control" id="bank_code" name="bank_code" >
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $bank)
                                                            @if ($bank['type'] == 'nuban')
                                                            <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    {!!Form::text('account_number', 'Account Number')->type('number')!!}
                                                    {!!Form::hidden('authorization_code', '')!!}
                                                </div>
                                            </div>
                                            <div class="row m-0">
                                                <div class="col-md-6">
                                                    {!!Form::text('account_name', 'Account Name')!!}
                                                    {!!Form::hidden('type', 'nuban')!!}
                                                </div>
                                                <div class="col-md-6">
                                                    {!!Form::select('currency', 'Currency Type', ['NGN' => 'Naira'])!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row m-0 p-5">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="row m-0 p-5">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="form-row  m-0 p-5 text-center">
                                    <div class="col-12">
                                        <button type="submit" id="saveTrip" class="btn btn-primary">Save</button>&nbsp;
                                        <button type="reset" class="btn btn-primary">Cancel</button>
                                    </div>
                                </div>
                                {!!Form::close()!!}
                            </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
    </div>
@endsection

@section('scripts')
<script>
    $("#account_number").change(function (){
        let theAccount = $("#account_number").val();
        let theBank = $("#bank_code").val();
        if (theAccount !="" & theBank != ""){
            $.ajax({
                url: "{{ route('resolveaccount') }}",
                type: "get", //send it through get method
                data: {
                  account_number: theAccount,
                  bank_code: theBank
                },
                success: function(response) {
                  //Do Something
                  $("#account_name").val(response.data.account_name);
                },
                error: function(xhr) {
                  //Do Something to handle error
                  alert("Error occured");
                  $("#account_name").val();
                }
              });
        }
    });
</script>
<style>

</style>
<script type="text/javascript">

</script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js' )}}"></script>
{!! $validator !!}
@endsection
