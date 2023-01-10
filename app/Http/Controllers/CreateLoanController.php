<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAttachments;
use App\Models\ClientInfo;
use App\Models\LoanInfo;
use App\Models\LoanStatusDate;
use App\Models\ClientLoanStatusDate;


use DB;
use Toastr;

class CreateLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    

    public function nameDropdown($id)
    {
        $name = ClientInfo::all();
        $selectedID = 2;

        return view('admin.pages.LoanManagement.createLoan', compact('selectedID', 'name'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admin.pages.LoanManagement.createLoan');
        // $name = ClientInfo::paginate(5);
        $name = ClientInfo::all()->where('status','=','Active');
        $selectedID = 2;

        return view('admin.pages.LoanManagement.createLoan', compact('selectedID', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->amount_apply <= 1999) {
            return back()->with('error','Loan Denied: The minimum loan is 2,000!');
        }elseif ($request->amount_apply >= 20001) {
            return back()->with('error','Loan Denied: The maximum loan is 20,000!');
        }

        $loaninfo = new LoanInfo;
        $loanstatusdate = new LoanStatusDate;
        
        

        $loaninfo->customer_id = $request->get('customer_name');
        $cua = $loaninfo->customer_id;
        $loanCheck = DB::table('loan_info')
        ->where('customer_id','=',$cua)
        ->where('current_status','!=','Completed')
        ->value('current_status');

        if ($loanCheck == 'Released') {
            return redirect()->back()->with('error','Error: Applying for another loan is not permitted because the member has an existing loan.');
        }else if ($loanCheck == 'Delinquent') {
            return redirect()->back()->with('error','Error: Applying another loan is not permitted because the member has an existing loan.');
        }else if ($loanCheck == 'Approved') {
            return redirect()->route('member.dashboard')->with('error',"Error: Applying another loan is not permitted because the member's loan is undergoing process.");
        }
        else if ($loanCheck == 'Applied') {
            return redirect()->back()->with('error','Error: Applying another loan is not permitted because the member has an existing loan.');
        }

        $loaninfo->amount_approved = $request->amount_apply;
        $loaninfo->contract_no_months = $request->months;
        $loaninfo->payment_term = $request->payment_term;
        $loaninfo->payment_term_result = $loaninfo->amount_approved / ($loaninfo->contract_no_months * 4);
        // $loaninfo->interest_rate = $request->interest_rate;
        $loaninfo->interest_rate = 0.12;
        $loaninfo->current_status = "Applied";
        $loaninfo->save();
        

        $loanstatusdate->id = $loaninfo->id;
        $loanstatusdate->loan_info_id = $loaninfo->id;
        // $loanstatusdate->loan_status_id = $loaninfo->customer_id;
        $loanstatusdate->status = "Applied";
        $loanstatusdate->status_date = $request->status_date;
        $loanstatusdate->actual_amount_on_status = $request->amount_apply;
        $loanstatusdate->save();

        $clientloanstatusdate = new ClientLoanStatusDate;
        $clientloanstatusdate->client_info_id = $request->get('customer_name');
        $clientloanstatusdate->status = "Applied";
        $clientloanstatusdate->status_date = $request->status_date;
        $clientloanstatusdate->actual_amount_on_status = $request->amount_apply;
        $clientloanstatusdate->save();
        
        
        
        
         


        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id')
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Loan Created Successfully');
            // return view('admin.pages.LoanManagement.index',compact('loanlist'))->with('status','Mark As Delinquent Successfully');

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
