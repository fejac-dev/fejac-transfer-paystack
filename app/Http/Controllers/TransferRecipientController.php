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
class TransferRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all Tranfer Recipient

        return view('transferrecipients.index');
    }

    /**
     * retreive all transfer recipient.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataList()
    {
        //get all Tranfer Recipient

        $thePaystack = new Paystack();
        $theTransferRecipient = $thePaystack->listTransferRecipient();

        return Datatables::of($theTransferRecipient['data'])
        ->addColumn('action', function ($transferRecipient) {
            return '<button type="button" class="btn btn-success btn-sm transfer_btn"> <i class="fa fa-pencil"></i>Transfer</button>'. ' <button type="button" class="btn btn-info btn-sm" onclick="alert('. $transferRecipient['id'] .');"> <i class="fa fa-binoculars"></i>Edit</button>';
        })
        ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resolveaccount()
    {
        //
        $thePaystack = new Paystack();
        return $thePaystack->resolveAccountNumber();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $messages= [
            'type.required' => 'Select an source terminal, field required',
            'name.required' => 'Supplier name required',
            'bank_code.required' => 'Select Bank',
            'account_number.required' => 'Account number required',
            'account_name.required' => 'Account name required',
        ];
        $rules =[
                'type' => 'required',
                'name' => 'required',
                'bank_code' => 'required',
                'account_number' => 'required|numeric',
                'account_name' => 'required',
        ];
        $thePaystack  = new Paystack();
        $theBankList = $thePaystack->listBank()['data'];
        $validator = JsValidator::make($rules,  $messages);
        return view('transferrecipients.create', [ 'banks'=>  $theBankList ,  'validator' => $validator]);
    }

    private function startdata() {
        $messages= [
            'trip_beginter.required' => 'Select an source terminal, field required',
        ];

        $rules =[
                'trip_beginter' => 'required',
            ];
        $validator = JsValidator::make($rules,  $messages);
        return [ 'validator' => $validator];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        //
         // validate
        $rules = array(
            'type' => 'required',
                'name' => 'required',
                'bank_code' => 'required',
                'account_number' => 'required',
        );
        //
        //dd( $rules);
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('/transferrecipient/create')
                ->withErrors($validator)->withInput(Input::except('password'));
        } else {
            $thePaystack = new Paystack();
            $thePaystack->createTransferRecipient();
            // redirect
            Session::flash('message', 'Successfully created transfer recipient');
            return redirect()->action('TransferRecipientController@index');
        }
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

    /**
     * Show the application dashboard.
     *
     * @return Current Balance
     */
    public function getbalance()
    {
        $theAccountBalance = ["currency" => "NGN", "balance"=> 0];
        try {
            $thePaystack = new Paystack();
            $theCurrentAcctBalance =  $thePaystack->checkBalance();
            if ( isset($theCurrentAcctBalance['data']) && count($theCurrentAcctBalance['data'])){
                $theAccountBalance = $theCurrentAcctBalance['data'][0];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }

        return $theAccountBalance;
    }
}
