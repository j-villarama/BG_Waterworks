@extends('admin.layouts.app')
@section('content')


<style>

    .card_custom{
        height: auto;
        width: 800px;
        background-color: white;
     }
    
    .linku{
        color:#073b3a;
    }

    .linku:hover{
        color:#a4caca;
    }

    .field-icon {
         float: right;
         margin-left: -25px;
         margin-top: -28px;
         position: relative;
         z-index: 25;
         color: black;
         }
    
    </style>
       
      <div class="page__container pt-4">
        <div class="card_custom">
            <div class="card-header">
                <div class="card-header-text">
                    <span><strong style="color: #073b3a">CHANGE PASSWORD</strong></span>
                </div>
                <div class="card-header-btn">
                    
                </div>
            </div>
            <div class="card-body">
    
            <form action="{{ url('admin/changeadminPassword/') }}" method="POST" enctype="multipart/form-data">    
                @csrf
                
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                            <div class="mb-3">
                                <label for="oldPasswordInput" class="form-label">Old Password</label>
                                <input name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" id="oldPasswordInput"
                                    placeholder="Old Password" minlength="0" maxlength="32">
                                    <span toggle="#oldPasswordInput" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                                @error('old_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="newPasswordInput" class="form-label">New Password</label>
                                <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="newPasswordInput"
                                    placeholder="New Password" minlength="0" maxlength="32">
                                    <span toggle="#newPasswordInput" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPasswordInput" class="form-label">Confirm New Password</label>
                                <input name="new_password_confirmation" type="password" class="form-control" id="confirmNewPasswordInput"
                                    placeholder="Confirm New Password" minlength="0" maxlength="32">
                                    <span toggle="#confirmNewPasswordInput" class="fa fa-fw fa-eye-slash field-icon toggle-password-icon"></span>
                            </div>

                            <div class="function__button d-flex justify-content-center mt-2">
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <a href="{{ route('admin.account_setting') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                            </div>

            </form>

    
                
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

@endsection