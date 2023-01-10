@extends('member.layouts.app')

@section('content')

<style>
body{
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

.thin { font-weight: 300; }
.thick { font-weight: 900; }

a {
  text-transform: uppercase;
  font-size: 20px;
  color: azure;
  text-decoration: none;
  position: relative;
  display: block;
}

[class^="link-"] {
  display: inline-block;
  margin: 1em
}

/* linkthree */
.link-3 a:before {
  content: '';
  border-bottom: solid 1px azure;
  position: absolute;
  bottom: 0; left: 0;
  width: 100%;
}

.link-3 a:hover:before {
  -webkit-transform: scaleY(4);
     -moz-transform: scaleY(4);
      -ms-transform: scaleY(4);
       -o-transform: scaleY(4);
          transform: scaleY(4);
}

.link-3 a:before {
  -webkit-transition: all 0.3s ease;
          transition: all 0.3s ease;
}

.field-icon {
         float: right;
         margin-left: -25px;
         margin-top: -25px;
         position: relative;
         z-index: 2;
         }

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
        -moz-appearance: textfield;
}         

</style>
{{-- @if(session()->has('error'))
    <div style="width: 100%; text-align:center;" class="alert alert-danger d-flex justify-content-center">
        {{ session()->get('error') }}
    </div>
@endif --}}
<div class="container justify-content-center d-flex flex-row-reverse">
<div class="form-floating client__info-section1">
    <div class="row d-flex justify-content-center">
        <div class="col-md-5">
            
                <div class="logo" style="padding-bottom:20px;">
                    <div class="row d-flex justify-content-center">
                        <img src="{{URL::asset('/image/BG Waterworks-02.png')}}" alt="" style="background: transparent; border:none; padding-left:14px; height:240px; width:260px; margin-top:-110px; margin-bottom:30px;">
                    </div>
                </div>

                @if(session()->has('error'))
                    <div style="width: 100%; text-align:center;" class="alert alert-danger d-flex justify-content-center">
                        {{ session()->get('error') }}
                    </div>
                @endif
                
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        

                        <div class="row mb-3 pt-3">
                            <label for="account_number" class="col-md-4 col-form-label text-md-end thick" style="color:azure">{{ __('Account Number') }}</label>

                            <div class="col-md-7">
                                <input maxlength = "12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="account_number" type="number" class="removeLetters form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ old('account_number') }}" required autocomplete="account_number" autofocus>

                                {{-- @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                                {{-- @if(session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end thick" style="color:azure">{{ __('Password') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" minlength="0" maxlength="32">
                                <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                            <div class="pt-3 thick"><a href="{{ route('password.request') }}" style="font-size: 10px;">Forgot Password?</a></div>
                                {{-- @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                                {{-- @if(session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif --}}
                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="text-center">
                                <button style="background-color: #cee5ea; border:#09504f; font-weight:900;" type="submit" class="btn btn-primary">
                                    <span style="color: black">{{ __('SIGN IN') }}</span>
                                </button>

                                
                                
                            </div>
                        </div>
                    </form>
                    
                </div>
            
                <div class="card-header d-flex justify-content-center">

                    <div class="link-3">
                        
                          <span class="thick"  style="font-size: 10px; color: azure">DON'T HAVE AN ACCOUNT?</span>
                          
                    </div>

                    <div>
                        <a style="background-color: #cee5ea; border:#09504f; font-weight:900;" class="btn btn-primary" href="{{ route('register') }}">
                          <span style="color: black" class="thick">sign up</span>
                        </a>  
                    </div>

                </div>
        </div>
    </div>
</div>

{{-- <div class="form-floating client__info-section2" style="margin-top: -5%;">
    <div class="row d-flex justify-content-end">
        <img src="{{URL::asset('/image/BG Waterworks-02.png')}}" alt="" style="background: transparent; border:none; padding-left:14px; height:300px; width:300px;">
    </div>
</div> --}}

    

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
