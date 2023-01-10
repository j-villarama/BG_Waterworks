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
    
    </style>
    
        <div class="page__container pt-4">
            {{-- <div class="back__button"><a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a></div> --}}
            
            <div class="card_custom">
               <nav class="nav nav-tabs">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-client-info">
                        <strong style="color: #073b3a">Select Available User Account</strong>
                    </button>
                    
    
                    
    
               </nav>
               <form name="FormName" action="{{ route('admin.customer.store_link_user',$data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane show active fade" id="tab-client-info">
                            <div class="tab__content-flex d-flex">
                                <div class="form-floating client__info-section1">
                                    @if (session('status'))
                                        <h6 class="alert alert-success">{{ session('status') }}</h6>
                                    @endif
                                    

                                    <br>
                                    
                                    <input hidden type="text" class="form-control form-control-sm" id="customer_id" name="customer_id" placeholder="{{ $data->id }}" value="{{ $data->id }}">
                                    
                                
                                    <div class="form-floating">
                                        <select required onclick="" class="form-select" id="available_accounts" aria-label="Floating label select example" name="available_accounts" >
                                            <option selected value="{{ null }}">Select</option>
                                            
                                                @foreach ($users as $users)
                                                    
                                                        <option value="{{ $users->id }}">{{ $users->email}}</option>
                                                    
                                                @endforeach
                                        </select>
                                        <label for="floatingSelect">Available Accounts</label>
                                    </div>
                                    <br>
                                    

                                    <div class="function__button">
                                        <button type="submit" class="btn btn-outline-success" value="Save">Save</button>
                                        <a href="{{ route('admin.borrowers.list') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                                    </div>

                                    
                                </div>



                            </div>
                        </div>
                        
                        
                        
                    </div>
               </form>
            </div> 
        </div>



        
@endsection