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

    @media (min-width: 320px) and (max-width: 480px) {

    .card_custom{
    height: auto;
    width: 100%;

    background: linear-gradient(145deg, #ffffff, #e6e6e6);  
    }

    .row{
        justify-content: center;
    }

    .card-header-text{
        text-align: center;
    }
    .card-header{
        justify-content: center;
    }

    }

    
    </style>
        

      <div class="page__container pt-4">
        <div class="card_custom">
            <div class="card-header">
                <div class="card-header-text">
                    <span><strong style="color: #073b3a">UPDATE EMAIL</strong></span>
                </div>
            </div>
            <div class="card-body">
    
            <form action="{{ url('member/changeEmail/') }}" method="POST" enctype="multipart/form-data">    
                @csrf
                
                @if(session()->has('status'))
                    <div style="width: 100%; text-align:center;" class="alert alert-success d-flex justify-content-center">
                        {{ session()->get('status') }}
                    </div>
                @endif

                <div class="container">
                    <div class="row">
                      <div class="col-8">
                        <div class="form-floating d-flex">
                            <input type="text" required class="form-control form-control-sm" id="floatingInput" name="email" placeholder="Email" value="{{ $select->email }}">
                            <label for="floatingInput">Email</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="function__button d-flex justify-content-center mt-2">
                            <button type="submit" class="btn btn-outline-success">Save</button>
                            <a href="{{ route('member.account_setting') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                        </div>
                      </div>
                    </div>
                </div>

            </form>

    
                
            </div>
            
        </div>
    </div>

@endsection