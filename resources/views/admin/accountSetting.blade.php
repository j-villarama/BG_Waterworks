@extends('admin.layouts.app')
@section('content')


<style>

    .card_custom{
        height: auto;
        width: 500px;
        background-color: white;
     }
    
     .card{
        width: 250px;
        height: 150px;
     }
    
     #card1{
        color: white; float:right;
     }
    
     #card1:hover{
        color: #508f8a;
     }
    
     #custom_button{
        border-radius: 0px;
        width: 100%;
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
                    <span><strong style="color: #073b3a">ACCOUNT SETTINGS</strong></span>
                </div>
                <div class="card-header-btn">
                    
                </div>
            </div>
            <div class="card-body">
    
                <div class="bg-linku my-2"><a class="linku" href="{{ route('admin.adminEmail') }}">Email <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
                <div class="bg-linku my-2"><a class="linku" href="{{ route('admin.updateadminPassword') }}">Password <i style="float: right;" class='bx bx-chevron-right bx-sm'></i></a></div>
    
                
            </div>
            
        </div>
    </div>

@endsection