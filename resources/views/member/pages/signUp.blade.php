@extends('member.layouts.app')
@section('content')

<style>

body{
    background-color: #073b3a;
}

.card_custom{
    height: auto;
    width: 1000px;
    /* border-radius: 15px; */
    background-color: white;
    /* background: linear-gradient(145deg, #ffffff, #e6e6e6); */
    /* box-shadow:  20px 20px 60px #d9d9d9,
                -20px -20px 60px #ffffff; */
 }

</style>

    <div class="page__container pt-4">
        {{-- <div class="back__button"><a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a></div> --}}
        <div class="card_custom">
           <nav class="nav nav-tabs">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-client-info">
                    <strong style="color: #073b3a">Sign Up</strong>
                </button>
           </nav>
           <form action="{{ route('admin.store_client') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tab-content">
                    <div class="tab-pane show active fade" id="tab-client-info">
                        <div class="tab__content-flex d-flex">
                            <div class="form-floating client__info-section1">
                                @if (session('status'))
                                    <h6 class="alert alert-success">{{ session('status') }}</h6>
                                @endif
                                <div class="form-floating">
                                    <input type="text" required class="form-control form-control-sm" id="floatingInput" name="account_number" placeholder="Account Number">
                                    <label for="floatingInput">Account Number</label>
                                </div>
                                <br>
                                <div class="form-floating">
                                    <input type="text" required class="form-control form-control-sm" id="floatingInput" name="contact_number" placeholder="Contact Number">
                                    <label for="floatingInput">Contact Number</label>
                                </div>
                                <br>
                                <div class="form-floating">
                                    <input type="text" required class="form-control form-control-sm" id="floatingInput" name="client_firstname" placeholder="First Name">
                                    <label for="floatingInput">First Name</label>
                                </div>
                                <br>
                                <div class="form-floating">
                                    <input type="text" required class="form-control form-control-sm" id="floatingInput" name="client_middlename" placeholder="Middle Name">
                                    <label for="floatingInput">Middle Name</label>
                                </div>
                                <br>
                                <div class="form-floating">
                                    <textarea class="form-control" required placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="client_adress"></textarea>
                                    <label for="floatingTextarea2">Address</label>
                                </div>
                            </div>
                            <div class="client__info-section2">
                                <div class="form-floating">
                                    <input type="text" required class="form-control form-control-sm" id="floatingInput" name="client_lastname" placeholder="Last Name">
                                    <label for="floatingInput">Last Name</label>
                                </div>
                                <br>
                                <label for="exampleFormControlInput1" class="form-label">Birthday</label>
                                <div class="input-group date" id="datepicker">
                                    <input type="text" required class="form-control" name="client_birthday" data-date-format="yy/mm/dd">
                                    <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                    </span>
                                    </span>
                                </div>
                                <br>
                                <div class="form-floating">
                                    <select class="form-select" required id="floatingSelect" aria-label="Floating label select example" name="client_gender">
                                        <option selected value="{{ null }}">Select</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                    <label for="floatingSelect">Select Gender</label>
                                </div>
                                <br>

                                {{-- <label for="formFileSm" class="form-label">Upload profile photo</label>
                                <input class="form-control form-control-sm" required type="file" name="client_profile_photo">
                                <br> --}}

                                <div class="function__button">
                                    <button type="submit" class="btn btn-outline-success" value="Save">Save</button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane show fade px-5 py-5" id="tab-profile">
                        <label for="formFileSm" class="form-label">Upload profile photo</label>
                        <input class="form-control form-control-sm" required type="file" name="client_profile_photo">
                    </div>
                    <div class="tab-pane show fade" id="tab-attachment">
                        TEST 3
                    </div>
                    <div class="tab-pane show fade" id="tab-notes">
                        TEST 4
                    </div> --}}
                </div>
           </form>
        </div> 
    </div>
@endsection