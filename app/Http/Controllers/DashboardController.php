<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientInfo;
use App\Models\LoanInfo;
use App\Models\LoanStatusDate;
use App\Models\LoanPayment;
use App\Models\CustomerUserAccount;
use App\Models\ClientLoanStatusDate;
use App\Models\User;
use App\Models\Chart;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loans = LoanInfo::all()->where('current_status','=','Applied');
        $total_loans = count($loans);

        
        $borrowers = ClientInfo::all();
        $total_borrowers = count($borrowers);

        $active_loans = LoanInfo::all()->where('current_status','=','Released');
        $total_active_loans = count($active_loans);

        $overdue = LoanPayment::all()->where('paid_status','=','Overdue');
        $total_overdue = count($overdue);

        $CurrentDate = Carbon::now()->toDateString();
          

            $Tblm = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
            ->where('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->groupBy(DB::raw("MONTH(updated_at)"))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->value('sums');
        
            $Tblw = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
            ->where('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->groupBy(DB::raw('WEEK(updated_at)'))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->orderBy('updated_at','DESC')
            ->value('sums');

            $Tbld = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
            ->where('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->groupBy(DB::raw('DAY(updated_at)'))
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->orderBy('updated_at','DESC')
            ->value('sums');

            

            $Tacm = LoanPayment::select(DB::raw('sum(amount) as sums'))
            ->where('paid_status','=','Paid')
            ->groupBy(DB::raw("MONTH(updated_at)"))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->value('sums');

            $Tacw = LoanPayment::select(DB::raw('sum(amount) as sums'))
            ->where('paid_status','=','Paid')
            ->groupBy(DB::raw('WEEK(updated_at)'))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->orderBy('updated_at','DESC')
            ->value('sums');

            $Tacd = LoanPayment::select(DB::raw('sum(amount) as sums'))
            ->where('paid_status','=','Paid')
            ->groupBy(DB::raw('DAY(updated_at)'))
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->groupBy(DB::raw("YEAR(updated_at)"))
            ->orderBy('updated_at','DESC')
            ->value('sums');

        // $userData = LoanInfo::groupBy('amount_approved')->select('amount_approved', DB::raw('count(*) as total'))->get();
        // $userData = LoanInfo::select(DB::raw('sum(amount_approved) as sums'),DB::raw("DATE_FORMAT(updated_at,'%m') as monthkey"))
        // ->whereYear('updated_at',date('Y'))
        // ->groupBy('monthKey')
        // ->pluck('sums');

        // $Mcost = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        // ->whereYear('updated_at',date('Y'))
        // ->groupBy(DB::raw("Month(updated_at)"))
        // ->pluck('sums');
        // $Mcost = array_map('intval', json_decode($Mcost, true));
        
        $Mcost = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->orWhere('current_status','=','Delinquent')
        ->groupBy(DB::raw("MONTH(updated_at)"))
        ->groupBy(DB::raw("YEAR(updated_at)"))
        ->pluck('sums');
        $Mcost = array_map('intval', json_decode($Mcost, true));


        $Wcost = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->orWhere('current_status','=','Delinquent')
        ->groupBy(DB::raw('WEEK(updated_at)'))
        ->groupBy(DB::raw("YEAR(updated_at)"))
        ->pluck('sums');
        $Wcost = array_map('intval', json_decode($Wcost, true));

        // DAYS
        $Dcost = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->orWhere('current_status','=','Delinquent')
        ->groupBy(DB::raw('DAY(updated_at)'))
        ->groupBy(DB::raw('MONTH(updated_at)'))
        ->groupBy(DB::raw("YEAR(updated_at)"))
        ->orderBy('updated_at')
        ->pluck('sums');
        $Dcost = array_map('intval', json_decode($Dcost, true));


        if (LoanInfo::exists()){
            $mos = LoanInfo::select('updated_at')
            ->where('current_status','=','Applied')
            ->orWhere('current_status','=','Approved')
            ->orWhere('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->orderBy('updated_at', 'asc')
            ->get();
        }else{
            $mos ='';
        }

        if (LoanInfo::exists()){
            $week = LoanInfo::select('updated_at')
            ->where('current_status','=','Applied')
            ->orWhere('current_status','=','Approved')
            ->orWhere('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->groupBy(DB::raw('WEEK(updated_at)'))
            ->orderBy('updated_at', 'asc')
            ->get();
        }else{
            $week ='';
        }

        if (LoanInfo::exists()){
            $daily = LoanInfo::select('updated_at')
            ->where('current_status','=','Applied')
            ->orWhere('current_status','=','Approved')
            ->orWhere('current_status','=','Released')
            ->orWhere('current_status','=','Completed')
            ->orWhere('current_status','=','Delinquent')
            ->orderBy('updated_at', 'asc')
            ->get();
        }else{
            $daily ='';
        }
        
        $client_to_inspect = ClientInfo::select('id','account_number', 'client_lastname', 'client_firstname')->paginate(5);

        return view('admin.pages.dashboard', compact('total_loans','total_borrowers','total_overdue','total_active_loans','CurrentDate', 'Mcost','Wcost','Dcost','mos','week','daily','client_to_inspect','Tblm','Tblw','Tbld','Tacm','Tacw','Tacd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
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
        //
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

    public function searchToInspect(Request $request)
    {
       
        $client_to_inspect=ClientInfo::where('client_firstname','LIKE','%'.$request->searchToInpect.'%')
        ->orwhere('client_lastname','LIKE','%'.$request->searchToInpect.'%')
        ->paginate(5);
        
        if($client_to_inspect->count() >=1){
            return view('admin.pages.tableToInspect', compact('client_to_inspect'))->render();
        }else{
            return response()->json([
                'status' => 'Nothing Found'
            ]);
        }
    }
    public function showSpecificGrpah($id)
    {

        // SPECIFIC GRAPH BY MONTH
        $SpecMos = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->groupBy(DB::raw("MONTH(updated_at)"))
        ->pluck('sums');
        $SpecMos = array_map('intval', json_decode($SpecMos, true));

        $SpecMosLabel = LoanInfo::select('updated_at')
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->orderBy('updated_at', 'asc')
        ->get();

        // SPECIFIC GRAPH BY WEEK
        $SpecWeek = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        // ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%W')"))
        ->groupBy(DB::raw('WEEK(updated_at)'))
        ->pluck('sums');
        $SpecWeek = array_map('intval', json_decode($SpecWeek, true));

        $SpecWeekLabel = LoanInfo::select('updated_at')
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->groupBy(DB::raw('WEEK(updated_at)'))
        ->orderBy('updated_at', 'asc')
        ->get();

        // SPECIFIC GRAPH BY DAY

        // DAYS
        $SpecDay = LoanInfo::select(DB::raw('sum(amount_approved) as sums'))
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        // ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
        // ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
        ->groupBy(DB::raw('DAY(updated_at)'))
        ->groupBy(DB::raw('MONTH(updated_at)'))
        ->orderBy('updated_at')
        ->pluck('sums');
        $SpecDay = array_map('intval', json_decode($SpecDay, true));

        $SpecDailyLabel = LoanInfo::select('updated_at')
        ->where('customer_id','=', $id)
        ->where('current_status','=','Released')
        ->orWhere('current_status','=','Completed')
        ->orderBy('updated_at', 'asc')
        ->get();

        if ($SpecMos == null) {
            return redirect()->back()->with('error','Member does not have a loan to inspect.');
        }
        

        $clientName = ClientInfo::select('client_lastname', 'client_firstname')
        ->where('id', '=' , $id)
        ->get();
        return view('admin.pages.specificChart', compact('SpecMos','SpecMosLabel', 'SpecWeek', 'SpecWeekLabel','SpecDay', 'SpecDailyLabel', 'clientName','id'));
    }

}
