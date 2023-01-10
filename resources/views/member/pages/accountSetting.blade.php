@extends('member.layouts.app2')
@section('content')


<style>

    .card_custom{
        height: auto;
        width: 100%;
        background-color: white;
     }
    
    .linku{
        color:#073b3a;
    }

    .linku:hover{
        color:#a4caca;
    }

    
    </style>
    
      <div class="page__container pt-4">
        <div class="card_custom">
            <div class="card-header">
                <div class="card-header-text">
                    <span><strong style="color: #073b3a">ACCOUNT INFORMATION</strong></span>
                </div>
                <div class="card-header-btn">
                    
                </div>
            </div>
            <div class="card-body">
    
                
              <div class="bg-linku my-2"><a class="linku" href="{{ route('member.updatePhoto') }}">Profile Photo <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
              <div class="bg-linku my-2"><a class="linku" href="{{ route('member.contactNo') }}">Contact Number <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
              <div class="bg-linku my-2"><a class="linku" href="{{ route('member.myemail') }}">Email <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
              <div class="bg-linku my-2"><a class="linku" href="{{ route('member.updatePassword') }}">Password <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
              


    
                
            </div>
            
        </div>
    </div>

@endsection