<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAttachments;
use App\Models\ClientInfo;
use App\Models\LoanInfo;
use App\Models\LoanStatusDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Toastr;
// use Request;

class CreateLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // private function generateDateRange(Carbon $start_date, Carbon $end_date)
    // {
    //     $dates = [];

    //     for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
    //         $dates[] = $date->format('Y-m-d');
    //     }

    //     return $dates;
    // }
    // for($iterate = 1; $iterate < 10; ++$iterate){
    //     echo "Week : " .$iterate. "\n";
    //     for($iterate2 = 1; $iterate2 <= 7; ++$iterate2){
    //         $newDateTime = Carbon::now()->addDays($iterate2);
    //     }
       
    // }
    public function weekly(Request $request)
    {
        $start = Carbon::now(); 
            for ($i = 0 ; $i < 3; $i++) 
            { 
                for ($j = 0 ; $j < 4; $j++) 
                { 
                    $dates[] = $start->copy()->addDays($j); 
                    if($j >= 3){
                        
                        $start = $start->addDays(4); 
                    }
                }
            }
            return view('admin.pages.LoanManagement.test', compact('dates'));
    }
    public function semiMonthly(Request $request)
    {
        $start = Carbon::now(); 
        for ($i = 0 ; $i <= 3; $i++) 
        { 
            for ($j = 0 ; $j <= 2; $j++) 
            { 
                $dates[] = $start->copy()->addDays($j); 
                if($j >= 2){
                    
                    $start = $start->addDays(2); 
                }
            }
        }
        return view('admin.pages.LoanManagement.test', compact('dates'));
    }
    public function monthly(Request $request)
    {
        $start = Carbon::now(); 
        $monthly = Carbon::now()->addMonth(1);

        $period = CarbonPeriod::create($start, $monthly);
        foreach ($period as $date) {
            $date->format('Y-m-d');
        }    
        $dates = $period->toArray();
        return view('admin.pages.LoanManagement.test', compact('dates'));

    }
    public function index()
    {
        // $now = Carbon::now();

        // $monthly = Carbon::now()->addMonth(2.3);

        // $newDateTime = Carbon::now()->addDays(7);

        // $period = CarbonPeriod::create($now, $monthly);
        // foreach ($period as $date) {
        //     for($i = 0; $i < count($period); $i++){
        //         if($period[$i] = 7){
        //             echo "hehe";
        //         }
        //     }
        //     $date->format('Y-m-d');
        // }
        // $dates = $period->toArray();
        // $per = $request->get('per');
        //  $start = Carbon::now(); 
        //     for ($i = 0 ; $i <= 10; $i++) 
        //     { 
        //         for ($j = 1 ; $j <= 7; $j++) 
        //         { 
        //             $dates[] = $start->copy()->addDays($j); 
        //             if($j >= 7){
                        
        //                 $start = $start->addDays(7); 
        //             }
        //         }
        //     }
        //     return view('admin.pages.LoanManagement.test', compact('dates'));
        
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
        $name = ClientInfo::all();
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
        $loaninfo = new LoanInfo;
        $loanstatusdate = new LoanStatusDate;
        

        $loaninfo->customer_id = $request->get('customer_name');
        $loaninfo->amount_approved = $request->amount_apply;
        $loaninfo->contract_no_months = $request->months;
        $loaninfo->payment_term = $request->payment_term;
        $loaninfo->payment_term_result = $request->payment_term_result;
        $loaninfo->interest_rate = $request->interest_rate;
        $loaninfo->current_status = "Approved";
        $loaninfo->save();
        

        
        $loanstatusdate->loan_info_id = $loaninfo->id;
        $loanstatusdate->status = "Approved";
        $loanstatusdate->status_date = $request->status_date;
        $loanstatusdate->actual_amount_on_status = $request->amount_apply;
        $loanstatusdate->save();
        
        
        
        // return redirect()->back()->with('status','Loan Created Successfully');


        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id')
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
        return view('admin.pages.LoanManagement.index',compact('loaninfo','loanstatusdate','loanlist'))->with('status','Mark As Delinquent Successfully');

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
