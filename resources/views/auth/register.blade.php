@extends('member.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
@section('content')

<style>
    body{
        color: azure;
        background-color: #cee5ea;
        background-image: url('/image/bg-background.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        
    }
    
    .card-body,.card-header{
        background: #0c5e5c;
       
    }

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
        -moz-appearance: textfield;
}

.field-icon {
         float: right;
         margin-left: -25px;
         margin-top: -25px;
         position: relative;
         z-index: 25;
         color: black;
         }

        
    
    </style>


<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8 d-flex justify-content-center">
            <div class="card" style="background-color: #09504f">
                <div class="card-header"><strong style="color: white; font-size:20px; z-index:1;">Sign Up</strong></div>
                
                <div class="card-body">
                    @if (session('status'))
                        <h6 class="alert alert-success">{{ session('status') }}</h6>
                    @endif

                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="account_number" class="col-md-4 col-form-label text-md-end">{{ __('Account Number') }}</label>

                            <div class="col-md-6">
                                <input maxlength = "12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="account_number" type="number" class="removeLetters form-control @error('account_number') is-invalid @enderror" name="account_number" value="" required autocomplete="account_number" autofocus>

                                @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input style="text-transform: capitalize;" maxlength = "32" id="name" onkeydown="return /[a-zA-Z ]/i.test(event.key)" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="" required autocomplete="middle_name" autofocus>

                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}


                        {{-- <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select class="form-select @error('gender') is-invalid @enderror" required id="gender" aria-label="Floating label select example" name="gender">
                                    <option selected value="{{ null }}">Select</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <label for="birthday" class="col-md-4 col-form-label text-md-end">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <div class="input-group date" id="datepicker">
                                    <input type="text" required class="form-control" name="birthday" data-date-format="yy/mm/dd">
                                    <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar pb-2"></i>
                                    </span>
                                    </span>
                                </div>
                                @error('birthday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Contact Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="name" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <textarea class="form-control" required placeholder="" id="floatingTextarea2" style="height: 100px" name="address"></textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        
                        


                        


                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" minlength="0" maxlength="32">
                                <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" minlength="0" maxlength="32">
                                <span toggle="#password-confirm" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                            </div>
                        </div>


                        <div class="col-12 d-flex justify-content-center pb-4">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                              <label class="form-check-label" for="invalidCheck">
                                Agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalLong">data privacy policy</a>
                              </label>
                              <div class="invalid-feedback">
                                You must agree before submitting.
                              </div>
                            </div>
                        </div>
                        
                        
                       
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button style="color:white; background-color: #cee5ea; border:#09504f" onclick="" type="submit" class="btn btn-outline-success" name="save_id" value="save_id">
                                    <span style="color: black;">{{ __('Register') }}</span>
                                </button>

                                <a style="color:black; background-color: #cee5ea; border:#09504f" href="{{ route('login') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                            </div>
                        </div>



                    </form>

                   
  
                        <!-- Modal -->
                        <div style="color: black" class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Data Privacy Policy</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                
                                    <div>
                                        <span><b>Purpose & Scope</b></span>
                                        <ul>
                                                BGWMPC website (Balubal – Gibanga Waterworks and Multipurpose 
                                                Cooperative) respects and values your privacy and the secrecy of your account 
                                                information with us. This Privacy Policy (“Policy”) informs you how we collect, use, 
                                                store, and process your personal data. We adhere to the data privacy principles of (1) 
                                                legitimate purpose – we only process upon your consent, in compliance with law or 
                                                contract; (2) transparency – we notify everything that happens to your data; and (3) 
                                                proportionality – collection is limited based on purpose.
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Collection of your Personal and Sensitive Personal Data</b></span>
                                        <ul>
                                                Personal Data refers to any information that identifies or is linkable to a natural 
                                                person. On the other hand, Sensitive Personal Data is any attribute that can distinguish, 
                                                qualify or classify a natural person from the others such as data relating to your 
                                                ethnicity, age, gender, health, religious or political beliefs, genetic or biometric data.
                                                <br>
                                                <br>
                                                We collect your Personal and Sensitive Personal Data when you register, sign-up 
                                                or use our website and services or contact us about loan. We also collect certain 
                                                document (i.e certificate of residency) and requirements needed for purposes of identity 
                                                verification and regulatory requirements by the Balubal – Gibanga Waterworks and 
                                                Multipurpose Cooperative (BGWMPC) when member are going to apply loan.
                                                
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Kinds of Data We Process</b></span>
                                        <ul>
                                                <b>Know-Your-Customer (KYC) / Identification Data:</b> refer to Personal Data and 
                                                Sensitive Personal Data we collect when you sign up or register to our services such as 
                                                full legal name, gender, date of birth, nationality, civil status, permanent address, 
                                                present address, mobile number, source of funds, gross annual income, and such other 
                                                information necessary to conduct due diligence and comply with BGWMC rules and 
                                                regulations as well as the personal data of the two Co- maker when borrower are going 
                                                to apply loan.                                                
                                                
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Data Storage</b></span>
                                        <ul style="margin-left: 20px">
                                            <li> We store Borrowers Data in secure and encrypted by authorized staff in the 
                                                cooperative and devices. </li>
                                            <li> We store physical copies of documents containing Borrowers Data in physical 
                                                secure vaults.</li>    
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Data Access</b></span>
                                        <ul>
                                            Borrowers Data can only be accessed by authorized personnel on a role-based 
                                            manner following the proportionality principle that authorized personnel can only access 
                                            Borrower Data they need for their role and purpose in the Cooperative.
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Data Use</b></span>
                                    </div>

                                    <div>
                                        <span><b>Borrowers Engagement</b></span>
                                        <ul style="margin-left: 20px">
                                            <li> We use your contact details with us to communicate with you about your 
                                                relationship with us. We may ask for feedback about our services.</li>    
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Due Diligence and Regulatory Compliance</b></span>
                                        <ul style="margin-left: 20px">
                                            <li> We may use Borrowers Data to evaluate your eligibility for Loan services.</li>
                                            <li> In assessing your ability to repay your loans, we conduct credit risk and 
                                                investigation and reporting on your credit history and account updates.</li>
                                            <li> We process Borrowers Data in compliance with legal obligations and statutory 
                                                requirements by Balubal Gibanga Waterworks and Multipurpose Cooperative 
                                                (BGWMPC).</li>        
                                            
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Protection and Security</b></span>
                                        <ul style="margin-left: 20px">
                                            <li> We process Borrower Data for your account protection against cybercrime, 
                                                identity theft, estafa, fraud, financial crimes such as money laundering, terrorism 
                                                financing, and tax fraud.</li>
                                            <li> We use your Personal Data such as name, age, nationality, IP address, home 
                                                address, and other Transactional Data to conduct profiling for detection of 
                                                suspicious activity on your account.
                                                </li>
                                            <li> We may reset your password or temporarily hold your online banking account to 
                                                protect you from detected suspected fraudulent activities.
                                                </li>        
                                            
                                        </ul>
                                    </div>

                                    <div>
                                        <span><b>Rights of Data Subjects</b></span>
                                    </div>

                                    <div>
                                        <span><i>Under the Data Privacy Act of 2012, you have the following rights:</i></span>
                                        <ul style="margin-left: 20px">
                                            <li> Right to be informed – you may demand the details as to how your Personal 
                                                Information is being processed or have been processed by the cooperative.
                                                </li>
                                            <li> Right to access – upon written request, you may demand reasonable access to 
                                                your Personal Information, which may include the contents of your processed 
                                                personal information, the manner of processing, sources where they were 
                                                obtained, recipients and reason of disclosure.
                                                
                                                </li>
                                            <li> Right to dispute – you may dispute inaccuracy or error in your Personal 
                                                Information in the BGWMPC systems through our contact center representatives.
                                                </li>
                                                
                                                <li> Right to object – you may suspend, withdraw, and remove your Personal 
                                                    Information in certain further processing, upon demand, which include your right 
                                                    to opt-out to any commercial communication or advertising purposes from the 
                                                    cooperative.
                                                    </li> 

                                                    <li> Right to data erasure – based on reasonable grounds, you have the right to 
                                                        suspend, withdraw or order blocking, removal or destruction of your personal 
                                                        data from the BGWMPC system.
                                                        </li>
                                                    
                                                        <li> Right to data portability – you have the right to obtain from the Bank your 
                                                            Personal Information in an electronic or structured format that is commonly used 
                                                            and allows for further use.</li>

                                                            <li>Right to be indemnified for damages – as data subject, you have every right to 
                                                                be indemnified for any damages sustained due to such violation of your right to 
                                                                privacy through inaccurate, false, unlawfully obtained or unauthorized use of your 
                                                                information.</li>

                                                                <li> Right to file a complaint – you may file your complaint or raise any concerns 
                                                                    with our Data Protection Officer.</li>
                                            
                                        </ul>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                                </div>
                            </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".toggle-password-icon").click(function() {
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $($(this).attr("toggle"));
          if (input.attr("type") == "password") {
          input.attr("type", "text");
          } else {
          input.attr("type", "password");
          }
       });
 </script>

<script>

    document.querySelector(".removeLetters").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
    
    </script>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: 'yy/mm/dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

<script>

    
</script>