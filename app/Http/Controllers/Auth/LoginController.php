<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientInfo;
use App\Models\CustomerUserAccount;
use App\Models\User;
use DB;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
      if (Auth::user()->user_type == 'Administrator')
      {
        return 'admin/dashboard';  // admin dashboard path
      } else {
        return 'member/dashboard';  // member dashboard path
      }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
      Auth::logout();
      return redirect('/login');
    }
    
    public function login(Request $request)
    {

      $account_number = $request->account_number;

      $chec_acc = DB::table('users')
      ->where('account_number','=',$account_number)->value('id');


            $checkID = DB::table('client_infos')
            ->where('account_number','=',$account_number)
            ->value('id');

            $update_user_id = DB::table('customer_user_account')
            ->where('customer_id','=',$checkID )
            ->update(array('user_id'=>$chec_acc));      

        $acc_check = User::where('account_number','=',$account_number)
        ->orWhere('email','=',$account_number)
        ->first();

        // if (is_null($acc_check->dob)) {
        //   return redirect()->route('login')->with('error','Access Denied: Your information is currently under verification. BG Waterworks Multi-Purpose Cooperative will contact you once approved. Thank you.');
        // }

        $input = $request->all();
  
        $this->validate($request, [
            'account_number' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->account_number, FILTER_VALIDATE_EMAIL) ? 'email' : 'account_number';
        if(auth()->attempt(array($fieldType => $input['account_number'], 'password' => $input['password'])) && Auth::user()->user_type == 'Administrator')
        {
          return redirect()->route('admin.dashboard');
        }else if(auth()->attempt(array($fieldType => $input['account_number'], 'password' => $input['password'])) && Auth::user()->dob == null)
        {
          return redirect()->route('login')->with('error','Access Denied: Your information is currently under verification. BG Waterworks Multi-Purpose Cooperative will contact you once approved. Thank you.');
        }

        else if(auth()->attempt(array($fieldType => $input['account_number'], 'password' => $input['password'])))
        {
          return redirect()->route('member.dashboard');
        }else{
          return back()->with('error','Access Denied: Please double-check the entry of your login information');
        }

        
        
          
    }

}
