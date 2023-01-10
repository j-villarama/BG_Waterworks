<?php

namespace App\Http\Controllers;

use App\Models\CustomerAttachments;
use App\Models\ClientInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ReportSection;
use DB;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Toastr;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $paidstatus = DB::table('loan_payment')
        // ->where('loan_info_id','=',$id)
        ->where('paid_status','=','Unpaid')
        ->where('due_date','<',Carbon::now())
        ->update(array('paid_status'=>'Overdue','is_overdue'=>1));

        $select = ClientInfo::orderByRaw("FIELD(status , 'Pending Application', 'Active', 'Inactive','Delinquent') ASC")->paginate(5);
        return view('admin.pages.clientList.index', compact('select'));

        if ($select->isEmpty()){
            return view('admin.pages.clientList.index');
        }
        else{
            
            return view('admin.pages.clientList.index', compact('select'));
        }

       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.clientList.partials.addClient');
    }

    public function attachment($id)
    {
        // $select = ClientInfo::find($id);
        // return view('admin.pages.clientList.partials.attachmentList', compact('select'));

        $files = CustomerAttachments::all();

        return view('admin.pages.clientList.partials.attachmentList', [
            'files' => $files
        ]);

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store_file(Request $request)
    {
        
        $fileName = auth()->id() . '_' . time() . '.'. $request->file->extension();  

        $request->file->move(public_path('attachments'), $fileName);

        CustomerAttachments::create([
            
            'customer_id' => auth()->id(),
            'files' => $fileName
        ]);

        return redirect()->back()->with('status','Client Added Successfully');  


       

   
        
    }

    
    public function store(Request $request)
    {
        // $clientinfo = new ClientInfo;

        // $clientinfo->account_number = $request->account_number;
        // $clientinfo->contact_number = $request->contact_number;
        // $clientinfo->client_firstname = $request->client_firstname;
        // $clientinfo->client_lastname = $request->client_lastname;
        // $clientinfo->client_middlename = $request->client_middlename;
        // $clientinfo->client_birthday = $request->client_birthday;
        // $clientinfo->client_gender = $request->client_gender;
        // $clientinfo->client_adress = $request->client_adress;

        $accnocheck = $request->account_number;
        $accnolength = strlen($accnocheck); 
        if ($accnolength < 12) {
            return redirect()->back()->with('error','Registration Denied: The account number must be at least 12 digits.');
        }

        $request->validate([
            'client_profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        // if($request->hasFile('client_profile_photo')){
        //     // Get filename with the extension
        //     $filenameWithExt = $request->file('client_profile_photo')->getClientOriginalName();
        //     // Get just filename
        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //     // Get just ext
        //     $extension = $request->file('client_profile_photo')->getClientOriginalExtension();
        //     // Filename to store
        //     $fileNameToStore= $filename.'_'.time().'.'.$extension;
        //     // Upload Image
        //     $path = $request->file('client_profile_photo')->move(public_path('client_photos'), $fileNameToStore);
        //  }
    

        // $imageName = time().'.'.$request->client_profile_photo->extension();  
        // $request->client_profile_photo->move(public_path('client_photos'), $imageName);
        // $clientinfo->client_profile_photo = $imageName;

        $img_name = 'client_photos_'.time().'.'.$request->client_profile_photo->getClientOriginalExtension();
        $request->client_profile_photo->move(public_path('client_photos/'), $img_name);
        $imagePath = $img_name; 
        $data = [
            'client_profile_photo'=>$imagePath,
            'account_number' => $request->account_number,
            'contact_number' => $request->contact_number,
            'client_firstname' => $request->client_firstname,
            'client_lastname' => $request->client_lastname,
            'client_middlename' => $request->client_middlename,
            'client_birthday' => $request->client_birthday,
            'client_gender' => $request->client_gender,
            'client_adress' => $request->client_adress,
        ]; 
        $add = ClientInfo::create($data); 

        // if($request->hasFile('client_profile_photo')){
        //     $clientinfo->client_profile_photo = $fileNameToStore;
        //  }
        
        // $clientinfo->save();

        return redirect()->back()->with('status','Client Added Successfully');   
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientInfo  $clientInfo
     * @return \Illuminate\Http\Response
     */
    public function show(ClientInfo $clientInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientInfo  $clientInfo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $select = ClientInfo::find($id);
        return view('admin.pages.clientList.partials.editClient', compact('select'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientInfo  $clientInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $clientInfo = ClientInfo::find($id);
        $clientInfo->client_firstname = $request->input('client_firstname');
        $clientInfo->client_lastname = $request->input('client_lastname');
        $clientInfo->client_middlename = $request->input('client_middlename');
        $clientInfo->client_birthday = $request->input('client_birthday');
        $clientInfo->client_gender = $request->input('client_gender');
        $clientInfo->client_adress = $request->input('client_adress');
        // $clientInfo->client_profile_photo = $extension = $request->file('client_profile_photo')->getClientOriginalExtension();
        

        // $request->validate([
        //     'client_profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        if ($request->hasFile('client_profile_photo')) {
            $img_name = 'client_photos_'.time().'.'.$request->client_profile_photo->getClientOriginalExtension();
            $request->client_profile_photo->move(public_path('client_photos/'), $img_name);
            $imagePath = $img_name;
            $data = [
                'client_profile_photo'=>$imagePath,
                // 'account_number' => $request->account_number,
                // 'contact_number' => $request->input('contact_number'),
                // 'client_firstname' => $request->input('client_firstname'),
                // 'client_lastname' => $request->input('client_lastname'),
                // 'client_middlename' => $request->input('client_middlename'),
                // 'client_birthday' => $request->input('client_birthday'),
                // 'client_gender' => $request->input('client_gender'),
                // 'client_adress' => $request->input('client_adress'),
                    ]; 
                    $update = ClientInfo::find($id)->update($data);
        }
        
        $clientInfo->update();

        return redirect()->back()->with('status','Client Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientInfo  $clientInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cua = DB::table('customer_user_account')
        ->where('customer_id','=',$id)
        ->value('user_id');

        $user = User::find($cua);

        if ($user != null) {
            $user->delete();
        }
        $select = ClientInfo::find($id);
        $select->delete();
        Toastr::success('Delete successful.', '');
        return redirect()->back()->with('status','Client Deleted Successfully');
    }

    public function delete_attachment($id)
    {
        
        $files = CustomerAttachments::find($id);
        $files->delete();
        Toastr::success('Delete successful.', '');
        return redirect()->back()->with('status','Attachment Deleted Successfully');

    }

    public function search(Request $request)
    {
       
        $select=ClientInfo::where('client_firstname','LIKE','%'.$request->search.'%')
        ->orwhere('client_lastname','LIKE','%'.$request->search.'%')
        ->paginate(5);
        
        if($select->count() >=1){
            return view('admin.pages.clientList.partials.table', compact('select'))->render();
        }else{
            return response()->json([
                'status' => 'Nothing Found'
            ]);
        }
    }


    public function sign_up()
    {

        return view('member.pages.signUp');

    }
    
    public function account_setting(){
        return view('admin.accountSetting');
    }

    public function adminEmail(){
        $id = Auth::id();
         $select = User::find($id);

        return view('admin.adminEmail',compact('select'));
    }

    public function changeadminEmail(Request $request){
        $id = Auth::id();
         $select = User::find($id);

         $change = $request->input('email');

         $data = [
            'email'=>$change,
        ]; 
        $update = User::find($id)->update($data);

        return redirect()->back()->with('status','Email Updated Successfully');

        return view('admin.adminEmail',compact('select'));
    }


    public function updateadminPassword(){
        $id = Auth::id();
         $select = User::find($id);

        return view('admin.adminPassword',compact('select'));
    }

    public function changeadminPassword(Request $request){

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

    public function viewReports(){


        $select = ReportSection::orderBy('created_at','desc')->paginate(5);
        return view('admin.reportSection', compact('select'));

        if ($select->isEmpty()){
            return view('admin.reportSection');
        }
        else{
            
            return view('admin.reportSection', compact('select'));
        }

    }

    public function loanHistory($id){


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
                return view('admin.LoanHistory', compact('loanlist','payHistory'));
            }
            else{
                
                return view('admin.LoanHistory', compact('loanlist','payHistory'));
            }
    }

    public function memberHistory(){

        $loanlist = DB::table('client_infos')
            ->join('loan_info', 'loan_info.customer_id', '=', 'client_infos.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_info.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('loan_info.current_status','=','Completed')
            ->paginate(5, array('me_id','client_infos.account_number','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));


            if ($loanlist->isEmpty()){
                return view('admin.memberHistory', compact('loanlist'));
            }
            else{
                
                return view('admin.memberHistory', compact('loanlist'));
            }
    }


    public function loanHistory1($id){


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
                return view('admin.LoanHistory1', compact('loanlist','payHistory'));
            }
            else{
                
                return view('admin.LoanHistory1', compact('loanlist','payHistory'));
            }
    }

    public function memberHistory1(){

        $loanlist = DB::table('loan_payment')
            ->join('client_infos', 'client_infos.id', '=', 'loan_payment.id')
            ->join('loan_info', 'loan_info.customer_id', '=', 'loan_payment.id')
            ->join('loan_status_date', 'loan_status_date.loan_info_id', '=', 'loan_payment.id')
            ->select('*','loan_info.id as me_id','loan_info.current_status','loan_payment.paid_status')
            ->orderBy('me_id','DESC')
            ->orderByRaw("FIELD(loan_info.current_status , 'Released', 'Approved','Denied', 'Delinquent','Completed') ASC")
            ->where('loan_info.current_status','=','Released')
            ->orWhere('loan_info.current_status','=','Completed')
            ->paginate(5, array('me_id','client_infos.account_number','client_infos.client_firstname','client_infos.client_lastname','loan_info.amount_approved','loan_info.current_status','loan_status_date.status_date'));


            if ($loanlist->isEmpty()){
                return view('admin.memberHistory1', compact('loanlist'));
            }
            else{
                
                return view('admin.memberHistory1', compact('loanlist'));
            }
    }


}
