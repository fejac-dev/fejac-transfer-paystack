<?php

namespace Mybakery\Http\Controllers;

use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;
use JsValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use Session;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('transfers.index');
    }


    /**
     * retreive all transfer recipient.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataList()
    {
        //get all Tranfer from Paystack

        $thePaystack = new Paystack();
        $theTransfer = $thePaystack->listTransfers();

        return Datatables::of($theTransfer['data'])
        ->addColumn('transaction_details', function ($transfer) {
            return  $transfer['currency'] . " " . ($transfer['amount']/100)  . " " . $transfer['recipient']['name']  ;
        })
        ->addColumn('bank_details', function ($transferDetails) {
            return $transferDetails['recipient']['details']['account_number']  . " ". $transferDetails['recipient']['details']['bank_name']  ;
        })
        ->addColumn('action', function ($transfer) {
            if ($transfer['status']!="success"){
                return '<button type="button" class="btn btn-success btn-sm" onclick="retryPayment('. $transfer['id'] .');"> <i class="fa fa-pencil"></i>Retry</button>';
            }else{
                return '';
            }

        })
        ->make(true);
    }

    public function confirmotp()
    {
        //get all Tranfer from Paystack
        $thePaystack = new Paystack();
        return $theTransfer = $thePaystack->finalizeTransfer();
    }

    public function resendotp()
    {
        //get all Tranfer from Paystack
        $thePaystack = new Paystack();
        return $theTransfer = $thePaystack->resendTransferOTP();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($recipient_code = "")
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Process transfer
        $thePaystack = new Paystack();
        return $thePaystack->initiateTransfer();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
