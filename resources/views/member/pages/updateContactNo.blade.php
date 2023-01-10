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

    input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
        -moz-appearance: textfield;
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
                    <span><strong style="color: #073b3a">UPDATE CONTACT NUMBER</strong></span>
                </div>
                
            </div>
            <div class="card-body">
    
            <form action="{{ url('member/changeContactNo/') }}" method="POST" enctype="multipart/form-data">    
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
                            <input maxlength = "11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="number" required class="removeLetters1 form-control form-control-sm" id="floatingInput" name="contact_number" placeholder="Contact Number" value="{{ $select->contact_number }}">
                            <label for="floatingInput">Contact Number</label>
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

    <script>
        document.querySelector(".removeLetters1").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
    </script>

@endsection