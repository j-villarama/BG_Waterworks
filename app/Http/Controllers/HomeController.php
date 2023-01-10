<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Middleware\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientInfo;
use App\Models\CustomerUserAccount;
use App\Models\LoanStatusDate;
use App\Models\LoanPayment;
use App\Models\ClientLoanStatusDate;
use App\Models\LoanInfo;
use App\Models\ReportSection;
use DB;
use PDF;
use File;
use Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $id = Auth::id();
        //  $cua = CustomerUserAccount::find($id, ['user_id']);
        //  $select = ClientInfo::find($cua); 
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);
        
        $idloaninfo = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->where('current_status','=','Released')
            ->value('id');
 

        $nextdueAmount = DB::table('loan_payment')
        ->where('loan_info_id','=',$idloaninfo)
        ->where('paid_status','=','Unpaid')
        ->value('amount');

        $overdue = LoanPayment::all()
        ->where('loan_info_id','=',$idloaninfo)
        ->where('paid_status','=','Overdue')
         // ->where('due_date','<',Carbon::now())
         ->sum('amount');


        $completedLoans = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.customer_id as me_id')
            ->where('loan_info.current_status','=','Completed')
            ->orWhere('loan_status_date.status','=','Completed')
            ->paginate(5, array('me_id','loan_status_date.actual_amount_on_status','loan_status_date.status_date','loan_status_date.updated_at'));




        $currentLoans = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_payment', 'loan_payment.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_payment.loan_info_id as me_id')
            ->where('customer_id','=',$cua)
            ->where([['customer_id','=',$cua],['paid_status','=','Unpaid']])
            ->orWhere([['customer_id','=',$cua],['paid_status','=','Overdue']])
            ->paginate(5, array('me_id','loan_payment.due_date','loan_payment.amount','loan_payment.paid_status','loan_info.payment_term','loan_info.amount_approved'));

            

            $Amount = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->where('current_status','=','Released')
            ->value('amount_approved');

            $paymentTerm = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->where('current_status','=','Released')
            ->value('payment_term');

            $dueCheck = LoanPayment::all();

            $loanCheck = DB::table('loan_info')
            // ->orderBy('current_status', 'ASC')
            ->where('customer_id','=',$cua)
            ->orderBy('id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            // ->orderByRaw("FIELD(loan_info.current_status , 'Applied', 'Approved','Released', 'Delinquent','Denied','Completed') ASC")
            // ->where('current_status','=','Applied')
            // ->orWhere('current_status','=','Approved')
            ->value('current_status');


            $custIdChk = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->value('customer_id');

            if (LoanPayment::exists()) {
                $nextdue = LoanPayment::all()
                ->where('due_date','>',Carbon::now())
                ->first()->where('loan_info_id','=',$idloaninfo)->where('paid_status','=','Unpaid')->value('due_date');
                return view('member.pages.home', compact('cua','id','select','currentLoans','completedLoans','idloaninfo','Amount','paymentTerm','overdue','nextdueAmount','nextdue','dueCheck','loanCheck','custIdChk'));
            }else {
                $nextdue = '';
                return view('member.pages.home', compact('cua','id','select','currentLoans','completedLoans','idloaninfo','Amount','paymentTerm','overdue','nextdueAmount','dueCheck','nextdue','loanCheck','custIdChk'));
            }

            
           
            





    }

    public function account_setting(){
        return view('member.pages.accountSetting');
    }

    public function updatePhoto(){
        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);

        return view('member.pages.updatePhoto',compact('select'));
    }

    public function changePhoto(Request $request){
        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);



        $img_name = 'client_photos_'.time().'.'.$request->client_profile_photo->getClientOriginalExtension();
        $request->client_profile_photo->move(public_path('client_photos/'), $img_name);
        $imagePath = $img_name;
        $data = [
            'client_profile_photo'=>$imagePath,
        ]; 
        $update = ClientInfo::find($cua)->update($data);
        

        return redirect()->back()->with('status','Profile Photo Updated Successfully');
    }

    public function contactNo(){
        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);

        return view('member.pages.updateContactNo',compact('select'));
    }

    public function changeContactNo(Request $request){
        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);

         $changeNO = $request->input('contact_number');

         $data = [
            'contact_number'=>$changeNO,
        ]; 
        $update = ClientInfo::find($cua)->update($data);

        return redirect()->back()->with('status','Contact Number Updated Successfully');

    }

    public function myemail(){
        $id = Auth::id();
         $select = User::find($id);

        return view('member.pages.updateEmail',compact('select'));
    }

    public function changeEmail(Request $request){
        $id = Auth::id();
         $select = User::find($id);

         $change = $request->input('email');

         $data = [
            'email'=>$change,
        ]; 
        $update = User::find($id)->update($data);

        return redirect()->back()->with('status','Email Updated Successfully');

        return view('member.pages.updateEmail',compact('select'));
    }

    public function updatePassword(){
        $id = Auth::id();
         $select = User::find($id);

        return view('member.pages.updatePassword',compact('select'));
    }

    public function changePassword(Request $request){

        $id = Auth::id();
         $select = User::find($id);
        
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('status','Password changed successfully!');

    }

    public function faqs(){

        return view('member.pages.faqs');
    }

    public function reports(){

        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);

        $find_report = ReportSection::orderBy('created_at','desc')->where('report_by','=',$select->account_number)->paginate(5);
        return view('member.pages.reportSection', compact('find_report'));

        if ($find_report->isEmpty()){
            return view('member.pages.reportSection');
        }
        else{
            
            return view('member.pages.reportSection', compact('find_report'));
        }

        // return view('member.pages.reportSection');
    }

    public function reports_destroy($id){

        $del = ReportSection::find($id);
        $del->delete();
        return redirect()->back()->with('status','Report is deleted successfully');
    }

    public function sendReport(Request $request){

        $id = Auth::id();
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);

         $report = $request->input('report');
         $reporter = $select->account_number;

         $data = [
            'report_description'=>$report,
            'report_by'=>$reporter,
        ];
        $add = ReportSection::create($data);  

        


         return redirect()->back()->with('status','Report is sent successfully');

    }

    public function receipt(){

        // return view('member.pages.receipt');

        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];

        
        $id = Auth::id();
        
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');
         $select = ClientInfo::find($cua);
        
        $idloaninfo = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->value('id');

        $loanfo = LoanInfo::find($idloaninfo);
        $statusDate = LoanStatusDate::find($idloaninfo);
        
        $nextdueAmount = DB::table('loan_payment')
        ->where('loan_info_id','=',$idloaninfo)
        ->value('amount');

        $overdue = LoanPayment::all()
        ->where('loan_info_id','=',$idloaninfo)
        ->where('paid_status','=','Overdue')
        ->sum('amount');

        $Amount = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->value('amount_approved');

        $paymentTerm = DB::table('loan_info')
            ->where('customer_id','=',$cua)
            ->value('payment_term');

            $current_time = Carbon::now()
            ->toDateString();

            $payment_id = DB::table('loan_payment')
            ->where('loan_info_id','=',$idloaninfo)
            ->where('paid_status','=','Unpaid')
            ->value('id');

            // $loanPayment = LoanPayment::find($payment_id);

            $maturity_date = LoanPayment::all()
            ->where('loan_info_id','=',$idloaninfo)
            ->last();

        $share_capital = $Amount * 0.05;
        $savings_deposit = $Amount * 0.016;
        $interest_on_loan = (($Amount * 0.12)/12)*$loanfo->contract_no_months;
        $service_fee = $Amount * 0.025;
        $miscellaneous_fee = $Amount * 0.005;
        $net_proceeds = $Amount - ($share_capital + $savings_deposit + $interest_on_loan + $service_fee + $miscellaneous_fee);

          
        $pdf = PDF::loadView('member.pages.receipt',compact('share_capital','savings_deposit','interest_on_loan','service_fee',
        'miscellaneous_fee','paymentTerm','Amount','select','statusDate','net_proceeds','current_time','loanfo','maturity_date'));
        return $pdf->download('receipt.pdf');
        
    }

    public function downloadPDF(){

        $download = public_path('filess/applicationform.docx');
        return Response::download($download);
    }

    public function apply_loan(){

        $id = Auth::id();
        
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');

        // $idLoan = DB::table('loan_info')
        // ->where('customer_id','=',$cua)
        // ->value('id');

        $loanCheck = DB::table('loan_info')
        ->where('customer_id','=',$cua)
        ->where('current_status','!=','Completed')
        ->value('current_status');

        if ($loanCheck == 'Released') {
            return redirect()->route('member.dashboard')->with('error','Error: Applying for another loan is not permitted because the member has an existing loan. Full Payment of the existing loan should be settled first.');
        }else if ($loanCheck == 'Delinquent') {
            return redirect()->route('member.dashboard')->with('error','Error: Applying another loan is not permitted because the member has an existing loan. Please settle the payment first or contact BGWMPC for further information.');
        }else if ($loanCheck == 'Approved') {
            return redirect()->route('member.dashboard')->with('error',"Error: Applying another loan is not permitted because the member's loan is undergoing process.");
        }
        else if ($loanCheck == 'Applied') {
            return redirect()->route('member.dashboard')->with('error','Error: You already applied a loan.');
        } else{
            return view('member.pages.applyLoan',compact('loanCheck'));
        }

        return view('member.pages.applyLoan',compact('loanCheck'));
    }

    public function applied_loan(Request $request){
        $id = Auth::id();
        
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');

        if ($request->amount_apply <= 1999) {
            return back()->with('error','Loan Denied: The minimum loan is 2,000!');
        }elseif ($request->amount_apply >= 20001) {
            return back()->with('error','Loan Denied: The maximum loan is 20,000!');
        }

        $loaninfo = new LoanInfo;
        $loanstatusdate = new LoanStatusDate;
        

        $loaninfo->customer_id = $cua;
        $loaninfo->amount_approved = $request->amount_apply;
        $loaninfo->contract_no_months = $request->months;
        $loaninfo->payment_term = $request->payment_term;
        $loaninfo->payment_term_result = $loaninfo->amount_approved / ($loaninfo->contract_no_months * 4);
        $loaninfo->interest_rate = 0.12;
        $loaninfo->current_status = "Applied";
        $loaninfo->save();
        

        $loanstatusdate->id = $loaninfo->id;
        $loanstatusdate->loan_info_id = $loaninfo->id;
        $loanstatusdate->status = "Applied";

        $loanstatusdate->status_date = $request->status_date;
        $loanstatusdate->actual_amount_on_status = $request->amount_apply;
        $loanstatusdate->save();

        $clientloanstatusdate = new ClientLoanStatusDate;
        $clientloanstatusdate->client_info_id = $cua;
        $clientloanstatusdate->status = "Applied";
        $clientloanstatusdate->status_date = $request->status_date;
        $clientloanstatusdate->actual_amount_on_status = $request->amount_apply;
        $clientloanstatusdate->save();

        // return redirect()->back()->with('status','Your Loan Application has been submitted. BG Waterworks Multi-Purpose Cooperative will contact you, once approved and released.');
        return redirect()->route('member.dashboard')->with('status','Your Loan Application has been submitted.');
    }


    public function transactionHistory($id){

        // $id = Auth::id();
        
        // $cua = DB::table('customer_user_account')
        // ->where('user_id','=',$id)
        // ->value('customer_id');
        //  $select = ClientInfo::find($cua);
        
        // $idloaninfo = DB::table('loan_info')
        //     ->where('customer_id','=',$cua)
        //     ->value('id');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('loan_info.id','=',$id)
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date','loan_status_date.remarks'));
            
            $payHistory = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_payment', 'loan_payment.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_payment.loan_info_id as me_id')
            ->where('paid_status','=','Paid')
            ->where('loan_info.id','=',$id)
            ->paginate(5, array('me_id','loan_payment.payment_date','loan_payment.due_date','loan_payment.amount','loan_payment.paid_status','loan_info.payment_term','loan_info.amount_approved'));
            
            

            if ($loanlist->isEmpty() || $payHistory->isEmpty()){
                return view('member.pages.transactionHistory', compact('loanlist','payHistory'));
            }
            else{
                
                return view('member.pages.transactionHistory', compact('loanlist','payHistory'));
            }
    }

    public function membHistory(){

        $id = Auth::id();
        
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('client_infos.id','=',$cua)
            ->where('loan_info.current_status','=','Released')
            ->orWhere('loan_info.current_status','=','Completed')
            ->paginate(5, array('me_id','client_infos.account_number','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));


            if ($loanlist->isEmpty()){
                return view('member.pages.memberHistory', compact('loanlist'));
            }
            else{
                
                return view('member.pages.memberHistory', compact('loanlist'));
            }
    }

    public function transactionHistory1($id){

        // $id = Auth::id();
        
        // $cua = DB::table('customer_user_account')
        // ->where('user_id','=',$id)
        // ->value('customer_id');
        //  $select = ClientInfo::find($cua);
        
        // $idloaninfo = DB::table('loan_info')
        //     ->where('customer_id','=',$cua)
        //     ->value('id');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('loan_info.id','=',$id)
            ->paginate(5, array('me_id','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date','loan_status_date.remarks'));
            
            $payHistory = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_payment', 'loan_payment.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_payment.loan_info_id as me_id')
            ->where('paid_status','=','Paid')
            ->where('loan_info.id','=',$id)
            ->paginate(5, array('me_id','loan_payment.payment_date','loan_payment.due_date','loan_payment.amount','loan_payment.paid_status','loan_info.payment_term','loan_info.amount_approved'));
            
            

            if ($loanlist->isEmpty() || $payHistory->isEmpty()){
                return view('member.pages.transactionHistory1', compact('loanlist','payHistory'));
            }
            else{
                
                return view('member.pages.transactionHistory1', compact('loanlist','payHistory'));
            }
    }

    public function membHistory1(){

        $id = Auth::id();
        
        $cua = DB::table('customer_user_account')
        ->where('user_id','=',$id)
        ->value('customer_id');

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('client_infos.id','=',$cua)
            ->where('loan_info.current_status','=','Completed')
            ->paginate(5, array('me_id','client_infos.account_number','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));


            if ($loanlist->isEmpty()){
                return view('member.pages.memberHistory1', compact('loanlist'));
            }
            else{
                
                return view('member.pages.memberHistory1', compact('loanlist'));
            }
    }

     
}
