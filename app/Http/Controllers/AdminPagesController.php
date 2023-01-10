<?php

namespace App\Http\Controllers;

use App\Models\CustomerAttachments;
use App\Models\ClientInfo;
use App\Models\LoanInfo;
use App\Models\LoanStatusDate;
use App\Models\LoanPayment;
use App\Models\CustomerUserAccount;
use App\Models\ClientLoanStatusDate;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class AdminPagesController extends Controller
{
    public function LoanManagement()
    {
       
        $paidstatus = DB::table('loan_payment')
        // ->where('loan_info_id','=',$id)
        ->where('paid_status','=','Unpaid')
        ->where('due_date','<',Carbon::now())
        ->update(array('paid_status'=>'Overdue','is_overdue'=>1));
        
        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            // ->orderBy('me_id', 'DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Applied', 'Approved','Released', 'Delinquent','Denied','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            // ->get(['client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date']);
            
            return view('admin.pages.LoanManagement.index', compact('loanlist'));

            if ($loanlist->isEmpty()){
                return view('admin.pages.LoanManagement.index');
            }
            else{
                
                return view('admin.pages.LoanManagement.index', compact('loanlist'));
            }
       
    }

    public function loan_applicants(){
        
        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->where('current_status','=','Applied')
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            
            
            return view('admin.pages.LoanManagement.loanApplicants', compact('loanlist'));

            if ($loanlist->isEmpty()){
                return view('admin.pages.LoanManagement.loanApplicants');
            }
            else{
                
                return view('admin.pages.LoanManagement.loanApplicants', compact('loanlist'));
            }
    }

    public function loan_overdue(){

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->join('loan_payment','loan_payment.loan_info_id','=','loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->where('paid_status','=','Overdue')
            
            
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date','loan_payment.paid_status'));
            
        
            
            return view('admin.pages.LoanManagement.loanOverdue', compact('loanlist'));

            if ($loanlist->isEmpty()){
                return view('admin.pages.LoanManagement.loanOverdue');
            }
            else{
                
                return view('admin.pages.LoanManagement.loanOverdue', compact('loanlist'));
            }

    }


    public function loan_payment($id,Request $request)
    {

        $data = LoanInfo::find($id);


        $datas = DB::table('loan_payment')->where('loan_info_id','=',$data->id)->where('paid_status','=','Unpaid')->orWhere('paid_status','=','Overdue')->first();
        

        // if($datas == null){
        //     return redirect()->back()->with('error','Error: There are no current loan.');
        // }

        if($datas == null){
            return redirect()->back()->with('error','This loan can be marked as complete.');
        }
        

        $loanPid = LoanPayment::all()
        ->where('loan_info_id','=',$data->id)
        ->where('paid_status','=','Unpaid')
        ->first();

        $overDue = LoanPayment::all()
        ->where('loan_info_id','=',$data->id)
        ->where('paid_status','=','Overdue')
        ->first();
        
        $payno = DB::table('loan_payment')
        ->where('loan_info_id','=',$id)
        ->select('id','loan_info_id','payment_no','paid_status','created_at')
        ->latest('created_at')->first();

        $paidstatus = DB::table('loan_payment')
        // ->where('loan_info_id','=',$id)
        ->where('paid_status','=','Unpaid')
        ->where('due_date','<',Carbon::now())
        ->update(array('paid_status'=>'Overdue','is_overdue'=>1));


        $nextdue = LoanPayment::all()
        ->where('due_date','>',Carbon::now())
        ->first()->where('loan_info_id','=',$id)->where('paid_status','=','Unpaid')->value('due_date');
        
        
        $dop = Carbon::now()->toDateString();
        $dueDate = DB::table('loan_payment')
        ->where('loan_info_id','=',$data->id)
        ->where('paid_status','=','Overdue')
        ->get();

        $getTerm = DB::table('loan_info')
        ->where('id','=',$data->id)
        ->value('payment_term_result');

        $overdueCount = count($dueDate);
        
        
            
        $i = 1;
        $totalPenalty = 0;
        while($i <= $overdueCount) {
                
                $x = LoanPayment::all()
                ->where('loan_info_id','=',$data->id)
                ->where('paid_status','=','Overdue')
                ->where('payment_no','=',$i)
                ->first();

                $end = Carbon::parse($x->due_date);
                $current = Carbon::now();
                $latedays = $end->diffInDays($current);

                $penalty = $x->amount * 0.001 * ($latedays / 30);
                // $x->amount = $x->amount + $penalty;

                $penalT = DB::table('loan_payment')
                ->where('loan_info_id','=',$data->id)
                ->where('paid_status','=','Overdue')
                ->where('payment_no','=',$i)
                ->update(array('penalty'=> $penalty));

                
                $totalPenalty+=$penalty;
                $i++;
                // $x->update();
        }  
        

        $overdue = LoanPayment::all()
        ->where('loan_info_id','=',$id)
        ->where('paid_status','=','Overdue')
        // ->where('due_date','<',Carbon::now())
        ->sum('amount');
           
        
        return view('admin.pages.LoanManagement.loanPayment',compact('data','payno','overdue','nextdue','dop','loanPid','dueDate','overdueCount','overDue','totalPenalty'));

        
    }

    public function store_loan_payment(Request $request, $id)
    {
        // $Unchecked = $request->overdue_check != null;
        // $Checked = $request->overdue_check == null;
        $loaninfo = LoanInfo::find($id);
        $dop = Carbon::now()->toDateString();
        $dateofpayment = $request->payment_date;
        $overdue = $request->overdue;


        // $loanlist = DB::table('client_infos')
        //     ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        //     ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        //     ->select('*','loan_info.id as me_id')
        //     ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));

        // return view('admin.pages.LoanManagement.index',compact('loanlist'))->with('status','Payment Succesful');


        if($request->overdue_check == false && $request->due_check == false){
            return redirect()->back()->with('status','Payment Denied: Please Select Payment Options');
        }

        else if($request->overdue_check == true && $request->overdue == 0){
            return redirect()->back()->with('status','Payment Denied: There Is No Overdue');
        }

        else if($request->overdue_check == true && $request->due_check == true && $request->overdue == 0 ){
            return redirect()->back()->with('status','Payment Denied: There Is No Loan To Pay');
        }

        // else if($request->overdue_check == true && $request->due_check == true && $request->overdue == 0 && $request->next_payment_due == null || $request->next_payment_due == 00 ){
        //     return redirect()->back()->with('status','Payment Denied: There Is No Loan To Pay');
        // }

        // else if($request->due_check == true && $request->overdue >= 1){
        //     return redirect()->back()->with('status','Payment Denied: There Is No Overdue');
        // }

        else if($request->overdue_check == true && $request->due_check == true){

            $loaninfo = LoanInfo::find($id);
            $updatePay = DB::table('loan_payment')
            ->where('loan_info_id','=',$loaninfo->id)
            ->where('paid_status','=','Overdue')
            ->update(array('paid_status'=>'Paid','payment_date'=> $dateofpayment));
            

            $payment = LoanPayment::all()
            ->where('loan_info_id','=',$loaninfo->id)
            ->where('paid_status','=','Unpaid')
            ->first();

            $payment->payment_date = $request->payment_date;
            $payment->paid_status = 'Paid';
            $payment->update(); 
            
            

            
        }

        else if($request->overdue_check == true && $request->due_check == false){

            $loaninfo = LoanInfo::find($id);
            $updatePay = DB::table('loan_payment')
            ->where('loan_info_id','=',$loaninfo->id)
            ->where('paid_status','=','Overdue')
            ->update(array('paid_status'=>'Paid','payment_date'=> $dateofpayment));
            
        }

        else if($request->overdue_check == false && $request->due_check == true){

            
            $payment = LoanPayment::all()
            ->where('loan_info_id','=',$loaninfo->id)
            ->where('paid_status','=','Unpaid')
            ->first();

            $payment->payment_date = $request->payment_date;
            $payment->paid_status = 'Paid';
            $payment->update(); 
            
        }
 
         
        

        // $loanlist = DB::table('client_infos')
        //     ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        //     ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        //     ->select('*','loan_info.id as me_id')
        //     ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
        // return view('admin.pages.LoanManagement.index',compact('loanlist'))->with('status','Payment Succesful');

        $loanlist = DB::table('client_infos')
        ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        ->select('*','loan_info.id as me_id')
        ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
        return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Loan Paid Successfully');

    }

    public function release($id)
    {
        $data = LoanInfo::find($id);

        if($data->current_status == 'Applied' || $data->current_status == 'Released' || $data->current_status == 'Delinquent' || $data->current_status == 'Completed'){
            return redirect()->back()->with('error','Error: Changing of status to Release is NOT permitted. The loan status should be Approved to release the loan.');
        }


        $present = Carbon::now()
        ->addMonth()
        ->toDateString();

        

        return view('admin.pages.LoanManagement.release',compact('data','present'));
    }

    public function update(Request $request, $id)
    {

        //weekly duedates//
        $p1 = Carbon::now()
            ->addDays(7)
            ->toDateString();
            $p2 = Carbon::now()
            ->addDays(14)
            ->toDateString();
            $p3 = Carbon::now()
            ->addDays(21)
            ->toDateString();
            $p4 = Carbon::now()
            ->addDays(28)
            ->toDateString();
            $p5 = Carbon::now()
            ->addDays(35)
            ->toDateString();
            $p6 = Carbon::now()
            ->addDays(42)
            ->toDateString();
            $p7 = Carbon::now()
            ->addDays(49)
            ->toDateString();
            $p8 = Carbon::now()
            ->addDays(56)
            ->toDateString();
            $p9 = Carbon::now()
            ->addDays(63)
            ->toDateString();
            $p10 = Carbon::now()
            ->addDays(70)
            ->toDateString();
            $p11 = Carbon::now()
            ->addDays(77)
            ->toDateString();
            $p12 = Carbon::now()
            ->addDays(84)
            ->toDateString();
            $p13 = Carbon::now()
            ->addDays(91)
            ->toDateString();
            $p14 = Carbon::now()
            ->addDays(98)
            ->toDateString();
            $p15 = Carbon::now()
            ->addDays(105)
            ->toDateString();
            $p16 = Carbon::now()
            ->addDays(112)
            ->toDateString();
            $p17 = Carbon::now()
            ->addDays(119)
            ->toDateString();
            $p18 = Carbon::now()
            ->addDays(126)
            ->toDateString();
            $p19 = Carbon::now()
            ->addDays(133)
            ->toDateString();
            $p20 = Carbon::now()
            ->addDays(140)
            ->toDateString();
            $p21 = Carbon::now()
            ->addDays(147)
            ->toDateString();
            $p22 = Carbon::now()
            ->addDays(154)
            ->toDateString();
            $p23 = Carbon::now()
            ->addDays(161)
            ->toDateString();
            $p24 = Carbon::now()
            ->addDays(168)
            ->toDateString();
            $p25 = Carbon::now()
            ->addDays(175)
            ->toDateString();
            $p26 = Carbon::now()
            ->addDays(182)
            ->toDateString();
            $p27 = Carbon::now()
            ->addDays(189)
            ->toDateString();
            $p28 = Carbon::now()
            ->addDays(196)
            ->toDateString();
            $p29 = Carbon::now()
            ->addDays(203)
            ->toDateString();
            $p30 = Carbon::now()
            ->addDays(210)
            ->toDateString();
            $p31 = Carbon::now()
            ->addDays(217)
            ->toDateString();
            $p32 = Carbon::now()
            ->addDays(224)
            ->toDateString();
            $p33 = Carbon::now()
            ->addDays(231)
            ->toDateString();
            $p34 = Carbon::now()
            ->addDays(238)
            ->toDateString();
            $p35 = Carbon::now()
            ->addDays(245)
            ->toDateString();
            $p36 = Carbon::now()
            ->addDays(252)
            ->toDateString();
            $p37 = Carbon::now()
            ->addDays(259)
            ->toDateString();
            $p38 = Carbon::now()
            ->addDays(266)
            ->toDateString();
            $p39 = Carbon::now()
            ->addDays(273)
            ->toDateString();
            $p40 = Carbon::now()
            ->addDays(280)
            ->toDateString();
            $p41 = Carbon::now()
            ->addDays(287)
            ->toDateString();
            $p42 = Carbon::now()
            ->addDays(294)
            ->toDateString();
            $p43 = Carbon::now()
            ->addDays(301)
            ->toDateString();
            $p44 = Carbon::now()
            ->addDays(308)
            ->toDateString();
            $p45 = Carbon::now()
            ->addDays(315)
            ->toDateString();
            $p46 = Carbon::now()
            ->addDays(322)
            ->toDateString();
            $p47 = Carbon::now()
            ->addDays(329)
            ->toDateString();
            $p48 = Carbon::now()
            ->addDays(336)
            ->toDateString();
        ///////////////////////////////////////////

        //Semi-monthly////////////////////////////
        $s1 = Carbon::now()
            ->addDays(15)
            ->toDateString();
            $s2 = Carbon::now()
            ->addDays(30)
            ->toDateString();
            $s3 = Carbon::now()
            ->addDays(45)
            ->toDateString();
            $s4 = Carbon::now()
            ->addDays(60)
            ->toDateString();
            $s5 = Carbon::now()
            ->addDays(75)
            ->toDateString();
            $s6 = Carbon::now()
            ->addDays(90)
            ->toDateString();
            $s7 = Carbon::now()
            ->addDays(105)
            ->toDateString();
            $s8 = Carbon::now()
            ->addDays(120)
            ->toDateString();
            $s9 = Carbon::now()
            ->addDays(135)
            ->toDateString();
            $s10 = Carbon::now()
            ->addDays(150)
            ->toDateString();
            $s11 = Carbon::now()
            ->addDays(165)
            ->toDateString();
            $s12 = Carbon::now()
            ->addDays(180)
            ->toDateString();
            $s13 = Carbon::now()
            ->addDays(195)
            ->toDateString();
            $s14 = Carbon::now()
            ->addDays(210)
            ->toDateString();
            $s15 = Carbon::now()
            ->addDays(225)
            ->toDateString();
            $s16 = Carbon::now()
            ->addDays(240)
            ->toDateString();
            $s17 = Carbon::now()
            ->addDays(255)
            ->toDateString();
            $s18 = Carbon::now()
            ->addDays(270)
            ->toDateString();
            $s19 = Carbon::now()
            ->addDays(285)
            ->toDateString();
            $s20 = Carbon::now()
            ->addDays(300)
            ->toDateString();
            $s21 = Carbon::now()
            ->addDays(315)
            ->toDateString();
            $s22= Carbon::now()
            ->addDays(330)
            ->toDateString();
            $s23 = Carbon::now()
            ->addDays(345)
            ->toDateString();
            $s24 = Carbon::now()
            ->addDays(360)
            ->toDateString();
        ////////////////////////////////////

        ///Monthly/////////////////////////
        $m1 = Carbon::now()
            ->addMonth()
            ->toDateString();
            $m2 = Carbon::now()
            ->addMonth(2)
            ->toDateString();
            $m3 = Carbon::now()
            ->addMonth(3)
            ->toDateString();
            $m4 = Carbon::now()
            ->addMonth(4)
            ->toDateString();
            $m5 = Carbon::now()
            ->addMonth(5)
            ->toDateString();
            $m6 = Carbon::now()
            ->addMonth(6)
            ->toDateString();
            $m7 = Carbon::now()
            ->addMonth(7)
            ->toDateString();
            $m8 = Carbon::now()
            ->addMonth(8)
            ->toDateString();
            $m9 = Carbon::now()
            ->addMonth(9)
            ->toDateString();
            $m10 = Carbon::now()
            ->addMonth(10)
            ->toDateString();
            $m11 = Carbon::now()
            ->addMonth(11)
            ->toDateString();
            $m12 = Carbon::now()
            ->addMonth(12)
            ->toDateString();   
        ////////////////////////////////////
        $loanpayment = new LoanPayment;


        $loaninfo = LoanInfo::find($id);
        $loaninfo->current_status = 'Released';
        $loaninfo->amount_approved = $request->amount_apply;
        $loaninfo->contract_no_months = $request->months;
        $loaninfo->update();

        $current_time = Carbon::now()
        ->toDateString();

         
        $loanSD = LoanStatusDate::find($id);
        
        $loanSD->status = 'Released';
        $loanSD->actual_amount_on_status = $request->amount_apply;
        $loanSD->status_date = $current_time;
        $loanSD->update();
        
        
        
        
        // return redirect()->back()->with('status','Released Successfully');

        $weekly = $loaninfo->amount_approved / ($loaninfo->contract_no_months * 4);
        $semi_monthly = $loaninfo->amount_approved / ($loaninfo->contract_no_months * 2);
        $monthly = $loaninfo->amount_approved / ($loaninfo->contract_no_months);

        //weekly
        if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 2 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 3 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 4 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 5 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 6 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 7 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 8 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 29, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p29, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 30, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p30, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 31, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p31, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 32, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p32, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 9 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 29, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p29, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 30, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p30, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 31, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p31, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 32, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p32, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 33, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p33, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 34, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p34, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 35, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p35, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 36, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p36, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 10 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 29, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p29, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 30, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p30, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 31, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p31, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 32, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p32, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 33, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p33, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 34, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p34, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 35, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p35, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 36, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p36, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 37, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p37, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 38, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p38, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 39, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p39, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 40, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p40, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 11 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 29, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p29, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 30, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p30, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 31, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p31, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 32, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p32, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 33, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p33, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 34, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p34, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 35, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p35, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 36, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p36, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 37, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p37, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 38, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p38, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 39, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p39, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 40, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p40, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 41, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p41, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 42, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p42, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 43, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p43, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 44, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p44, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Weekly' && $loaninfo->contract_no_months == 12 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p24, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 25, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p25, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 26, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p26, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 27, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p27, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 28, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p28, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 29, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p29, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 30, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p30, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 31, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p31, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 32, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p32, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 33, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p33, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 34, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p34, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 35, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p35, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 36, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p36, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 37, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p37, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 38, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p38, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 39, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p39, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 40, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p40, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 41, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p41, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 42, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p42, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 43, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p43, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 44, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p44, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 45, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p45, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 46, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p46, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 47, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p47, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 48, 'amount'=>$weekly, 'payment_date'=>null, 'due_date'=>$p48, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

         //semi-monthly
        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 2 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 3 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 4 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 5 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 6 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 7 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 8 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s16, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 9 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s18, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 10 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s20, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 11 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s22, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        else if ($loaninfo->payment_term == 'Semi-Monthly' && $loaninfo->contract_no_months == 12 ) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s12, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 13, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s13, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 14, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s14, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 15, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s15, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 16, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s16, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 17, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s17, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 18, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s18, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 19, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s19, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 20, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s20, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 21, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s21, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 22, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s22, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 23, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s23, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 24, 'amount'=>$semi_monthly, 'payment_date'=>null, 'due_date'=>$s24, 'paid_status'=>'Unpaid'],

            ];
            
            DB::table('loan_payment')->insert($loans);


        }

        //monthly
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 2) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 3) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 4) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 5) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 6) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 7) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 8) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m8, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 9) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m9, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 10) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m10, 'paid_status'=>'Unpaid'],
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 11) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m11, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }
        else if ($loaninfo->payment_term == 'Monthly' && $loaninfo->contract_no_months == 12) {
            
            $loans = [
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 1, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m1, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 2, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m2, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 3, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m3, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 4, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m4, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 5, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m5, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 6, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m6, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 7, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m7, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 8, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m8, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 9, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m9, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 10, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m10, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 11, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m11, 'paid_status'=>'Unpaid'],
                ['loan_info_id'=>$loaninfo->id, 'payment_no'=> 12, 'amount'=>$monthly, 'payment_date'=>null, 'due_date'=>$m12, 'paid_status'=>'Unpaid'],
                
            ];
            
            DB::table('loan_payment')->insert($loans);

        }


        

        


        // $loanpayment->save();






        // $loanlist = DB::table('client_infos')
        //     ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        //     ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        //     ->select('*','loan_info.id as me_id')
        //     ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
        // return view('admin.pages.LoanManagement.index',compact('loanlist'))->with('status','Mark As Delinquent Successfully');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved', 'Delinquent','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Loan Released Successfully');
    }

    public function delinquent($id)
    {
        
        $loansd = LoanStatusDate::find($id);
        return view('admin.pages.LoanManagement.delinquent',compact('loansd'));
    }

    public function update_delinquent(Request $request, $id)
    {
        
        $loansd = LoanStatusDate::find($id);
        $loansd->remarks = $request->input('reason');
        $loansd->status = 'Delinquent';

        $loaninfo = LoanInfo::find($id);
        $loaninfo->current_status = 'Delinquent';

        // $complete_loan = DB::table('loan_payment')
        //     ->where('loan_info_id','=',$id )
        //     ->update(array('paid_status'=>'Paid','is_overdue'=>0));
        
 
        

        $loaninfo->update();
        $loansd->update();
        // return redirect()->back()->with('status','Mark As Delinquent Successfully');


        // $loanlist = DB::table('client_infos')
        //     ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        //     ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        //     ->select('*','loan_info.id as me_id')
        //     ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));

        // return view('admin.pages.LoanManagement.index',compact('loansd','loanlist'))->with('status','Mark As Delinquent Successfully');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved', 'Delinquent','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Marked As Deliquent');

    }


    public function denied($id)
    {
        
        $loansd = LoanStatusDate::find($id);
        return view('admin.pages.LoanManagement.denied',compact('loansd'));
    }

    public function update_denied(Request $request, $id)
    {
        
        $loansd = LoanStatusDate::find($id);
        $loansd->remarks = $request->input('reason');
        $loansd->status = 'Denied';

        $loaninfo = LoanInfo::find($id);
        $loaninfo->current_status = 'Denied';

        $loaninfo->update();
        $loansd->update();
       
        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved', 'Delinquent','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Marked As Denied');

    }


    public function complete($id)
    {

        $data = LoanInfo::find($id);

        if($data->current_status == 'Applied' || $data->current_status == 'Approved' || $data->current_status == 'Delinquent' || $data->current_status == 'Completed'){
            return redirect()->back()->with('error','Error: Changing of status to Completed is NOT permitted. The loan status should be Released to complete the loan.');
        }

        $loansd = new LoanStatusDate;
        $loansd = LoanStatusDate::find($id);
        
        return view('admin.pages.LoanManagement.complete',compact('loansd'));
    }

    public function complete_update(Request $request, $id)
    {
        
        $loansd = LoanStatusDate::find($id);
        $loansd->remarks = $request->input('remarks');
        $loansd->status = 'Completed';

        $loaninfo = LoanInfo::find($id);
        $loaninfo->current_status = 'Completed';
        $dop = Carbon::now()->toDateString();

        $complete_loan = DB::table('loan_payment')
            ->where('loan_info_id','=',$id )
            ->update(array('paid_status'=>'Paid','is_overdue'=>0,'payment_date'=>$dop));  
        
 
        

        $loaninfo->update();
        $loansd->update();
        // return redirect()->back()->with('status','Mark As Completed Successfully');


        // $loanlist = DB::table('client_infos')
        //     ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
        //     ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
        //     ->select('*','loan_info.id as me_id')
        //     ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
        // return view('admin.pages.LoanManagement.index',compact('loansd','loanlist'))->with('status','Mark As Delinquent Successfully');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved', 'Delinquent','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Marked As Completed');
    }

    public function approved(Request $request, $id)
    {
        $data = LoanInfo::find($id);

        if($data->current_status == 'Released' || $data->current_status == 'Completed' || $data->current_status == 'Approved'){
            return redirect()->back()->with('error','Error: Changing of status to Approved is NOT permitted. The loan status should be Applied first.');
        }
        
        $loansd = LoanStatusDate::find($id);
        $loansd->status = 'Approved';

        $loaninfo = LoanInfo::find($id);
        $loaninfo->current_status = 'Approved';

        $loaninfo->update();
        $loansd->update();

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved', 'Delinquent','Completed') ASC")
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));
            return redirect()->route('admin.LoanManagement',compact('loanlist'))->with('status','Marked As Approved');
    }



    public function search(Request $request)
    {
       
        // $loanlist=DB::where('client_firstname','LIKE','%'.$request->loanSearch.'%')
        // ->orwhere('client_lastname','LIKE','%'.$request->loanSearch.'%')
        // ->paginate(5);
        
        // if($loanlist->count() >=1){
        //     return view('admin.pages.LoanManagement.table', compact('loanlist'))->render();
        // }else{
        //     return response()->json([
        //         'status' => 'Nothing Found'
        //     ]);
        // }

        
        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->where('client_infos.client_firstname','LIKE','%'.$request->searchloan.'%')
            // ->orWhere('loan_info.current_status','LIKE','%'.$request->$loanSearch.'%')
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));

        
        if($loanlist->count() >=1){
                return view('admin.pages.LoanManagement.table', compact('loanlist'))->render();
            }else{
                return response()->json([
                    'status' => 'Nothing Found'
                ]);
            }

    }
    
    // public function store_id(Request $request, $id)
    // {

      
    //     $CUA = new CustomerUserAccount;

    //     $clientid = ClientInfo::find($id);
    //     $userid = User::find($id);

    //     $CUA->customer_id = $request->clientid;
    //     $CUA->user_id = $request->userid;
        
    //     $CUA->save();   
      
        
        
        
    // }

    public function create_user($id)
    {
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('user_id');

        $data = ClientInfo::find($id);
        $taken =CustomerUserAccount::all();
        $checkacc = DB::table('client_infos')
        ->where('id','=',$id)
        ->value('account_number');

        $users =User::all()
        ->where('user_type','=','Member')
        ->where('dob','!=','Taken')
        ->where('account_number','=',$checkacc);

        return view('admin.pages.clientList.partials.createUser', compact('users','data'));

    }

    public function store_link_user(Request $request, $id)
    {
        
        $CUA = new CustomerUserAccount;
        $CUA->customer_id = $request->customer_id;
        $CUA->user_id = $request->available_accounts;
        $CUA->save();
        
        $user = DB::table('users')
        ->where('id','=',$request->available_accounts)
        ->update(array('dob'=>'Taken'));

        $status = DB::table('client_infos')
        ->where('id','=',$id)
        ->update(array('status'=>'Active'));
        
        return redirect()->back()->with('status','Linked Successfully'); 

    }


}
