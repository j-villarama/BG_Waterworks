@extends('admin.layouts.app')
@section('content')
 

<style>

    .card_custom{
        height: auto;
        width: 1000px;
        border-radius: 15px;
        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        box-shadow:  20px 20px 60px #d9d9d9,
                    -20px -20px 60px #ffffff;
     }
    
    </style>
    
        <div class="page__container pt-4">
            
            <div class="card_custom">
               <nav class="nav nav-tabs">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-client-info">
                        <strong style="color: #073b3a">Mark Customer As Delinquent</strong>
                    </button>
                    
    
                    
    
               </nav>
               <form name="FormName" action="{{ url('admin/delinquent_update/'.$loansd->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane show active fade" id="tab-client-info">
                            <div class="tab__content-flex d-flex">
                                <div class="form-floating client__info-section1">
                                    @if (session('status'))
                                        <h6 class="alert alert-success">{{ session('status') }}</h6>
                                    @endif
                                    

                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Reason</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                                    </div>

                                    <br>
                                    <div class="function__button">
                                        <button type="submit" class="btn btn-outline-success" value="Save">Save</button>
                                        <a href="{{ route('admin.LoanManagement') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                                    </div>

                            </div>
                        </div>
                        
                        
                        
                    </div>
               </form>
            </div> 
        </div>



        
@endsection