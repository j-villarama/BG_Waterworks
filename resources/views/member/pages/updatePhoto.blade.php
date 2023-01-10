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

.form-label{
    text-align: center;
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
                    <span><strong style="color: #073b3a">EDIT PROFILE PICTURE</strong></span>
                </div>
            </div>
            <div class="card-body">
    
            <form action="{{ url('member/changePhoto/') }}" method="POST" enctype="multipart/form-data">    
                @csrf
                @if(session()->has('status'))
                    <div style="width: 100%; text-align:center;" class="alert alert-success d-flex justify-content-center">
                        {{ session()->get('status') }}
                    </div>
                @endif
                <div class="profile__photo-content">
                    <div class="profile__photo-img">
                        <img src="{{ url('client_photos/' . $select->client_profile_photo) }}" class="client__photo-img" >
                    </div>
                    <div class="profile__photo-select">
                        <label for="formFileSm" class="form-label">Select Client Photo</label>
                        <input class="form-control form-control-sm" type="file" name="client_profile_photo" value="">
                    </div>
                </div>
              
                <div class="function__button d-flex justify-content-center">
                    <button type="submit" class="btn btn-outline-success">Save</button>
                    <a href="{{ route('member.account_setting') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                </div>
            </form>

    
                
            </div>
            
        </div>
    </div>

@endsection