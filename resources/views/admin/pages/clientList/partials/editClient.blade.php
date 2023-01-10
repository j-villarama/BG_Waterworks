@extends('admin.layouts.app')
@section('content')

<style>

.card_custom{
    height: auto;
    width: 1000px;
    background-color: white;
    /* border-radius: 15px;
    background: linear-gradient(145deg, #ffffff, #e6e6e6);
    box-shadow:  20px 20px 60px #d9d9d9,
                -20px -20px 60px #ffffff; */
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
    <div class="page__container pt-4">
        {{-- <div class="back__button"><a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a></div> --}}
        <div class="card_custom">
    
           <nav class="nav nav-tabs">
                
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-client-info">
                    <strong style="color: #073b3a">Customer Information</strong>
                </button>
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-profile">
                    <strong style="color: #073b3a">Profile Photo</strong>
                </button>
                {{-- <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-attachment">
                    Attachments
                </button>
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-notes">
                    Notes
                </button> --}}
           </nav>
           <form action="{{ url('admin/update_client/'.$select->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="tab-content">
                    <div class="tab-pane active show fade" id="tab-client-info">
                        <div class="tab__content-flex d-flex">
                            <div class="client__info-section1">
                                @if (session('status'))
                                    <h6 class="alert alert-success">{{ session('status') }}</h6>
                                @endif
                                <label for="exampleFormControlInput1" class="form-label">First Name</label>
                                <input maxlength = "30" onkeydown="return /[a-zA-Z ]/i.test(event.key)" type="text" class="form-control " id="exampleFormControlInput1" name="client_firstname" value="{{ $select->client_firstname }}">
                                <br>
                                <label for="exampleFormControlInput1" class="form-label">Middle Initial</label>
                                <input minlength="1" maxlength="1"  onkeydown="return /[a-z]/i.test(event.key)" type="text" class="form-control " id="exampleFormControlInput1" name="client_middlename" value="{{ $select->client_middlename }}">
                                <br>
                                <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                                <textarea type="text" class="form-control" id="exampleFormControlTextarea1" rows="3" name="client_adress" value="{{ $select->client_adress }}">{{ $select->client_adress }}</textarea>
                            </div>
                            <div class="client__info-section2">
                                <label for="exampleFormControlInput1" class="form-label">Last Name</label>
                                <input maxlength = "30" onkeydown="return /[a-z]/i.test(event.key)" type="text" class="form-control" id="exampleFormControlInput1" name="client_lastname" value="{{ $select->client_lastname }}">
                                <br>
                                <label for="exampleFormControlInput1" class="form-label">Birthday</label>
                                {{-- <div class="input-group date" id="datepicker">
                                    <input type="text" class="form-control" name="client_birthday" data-date-format="yy/mm/dd" value="{{ $select->client_birthday }}">
                                    <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                    </span>
                                    </span>
                                </div> --}}

                                <div class="input-group date">
                                    <input onkeydown="return false" required type="date" class="form-control" id="date_picker" name="client_birthday" data-date-format="yy/mm/dd" placeholder="Date" value="{{ $select->client_birthday }}">
                                    <span class="input-group-append">
                                    {{-- <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i> --}}
                                    </span>
                                    </span>
                                </div>
                                <br>
                                <label for="exampleFormControlInput1" class="form-label">Gender</label>
                                <select class="form-select" aria-label="Default select example" name="client_gender" value="{{ $select->client_gender }}">
                                    <option selected>{{ $select->client_gender }}</option>
                                    <option value="1" name="male">Male</option>
                                    <option value="2" name="female">Female</option>
                                </select>
                                <br>
                                <div class="function__button">
                                    <button type="submit" class="btn btn-outline-success">Update Record</button>
                                    <a href="{{ route('admin.borrowers.list') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="tab-profile">
                        <div class="profile__photo-content">
                            <div class="profile__photo-img">
                                <img src="{{ url('client_photos/' . $select->client_profile_photo) }}" class="client__photo-img" >
                            </div>
                            <div class="profile__photo-select">
                                <label for="formFileSm" class="form-label">Select Client Photo</label>
                                <input class="form-control form-control-sm" type="file" name="client_profile_photo" value="{{ $select->client_profile_photo }}">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="tab-attachment">
                        TEST 3
                    </div>
                    <div class="tab-pane show fade" id="tab-notes">
                        TEST 4
                    </div>
            </div>
           </form>
        </div> 
    </div>

    <script language="javascript">
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
    
        today = yyyy + '-' + mm + '-' + dd;
        $('#date_picker');
    </script>

    <script>
        document.querySelector(".removeLetters").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
    </script>

<script>
    document.querySelector(".removeLetters1").addEventListener("keypress", function (evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
});
</script>
@endsection
