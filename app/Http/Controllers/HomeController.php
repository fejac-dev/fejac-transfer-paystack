<?php

namespace Mybakery\Http\Controllers;

use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $theAccountBalance = ["currency" => "NGN", "balance"=> 0];
        $theTransferRecipientCount = 0;
        $theTransfers = 0;
        $theSuccessfulTransfer = "";
        try {
            $thePaystack = new Paystack();
            $theCurrentAcctBalance =  $thePaystack->checkBalance();
            if ( isset($theCurrentAcctBalance['data']) && count($theCurrentAcctBalance['data'])){
                $theAccountBalance = $theCurrentAcctBalance['data'][0];
            }
            $theTransferRecipient = $thePaystack->listTransferRecipient()['data'];
            $theTransfers = $thePaystack->listTransfers();
            if (count($theTransfers['data'])){
                $theAllTransfer = collect($theTransfers['data']);
                $theSuccessfulTransfer = $theAllTransfer->where('status' , 'success')->count();
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }

        return view('home', ["currentBalance" =>  $theAccountBalance, 'transferRecipient' =>$theTransferRecipient, 'transferCount' => count($theTransfers['data']), 'successfulTransfer' => $theSuccessfulTransfer] );
    }
}
