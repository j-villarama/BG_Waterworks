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
                        <strong style="color: #073b3a">Payment for this Customers</strong>
                    </button>
                    
    
                    
    
               </nav>
               
               
               <form name="FormName" action="{{ url('admin/store_loanpayment/'.$data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane show active fade" id="tab-client-info">
                            <div class="tab__content-flex d-flex">
                                {{-- <div class="form-floating client__info-section1">
                                   
                                    
                                        
                                    <br>
                                    
                                    <div class="form-floating">
                                        <select class="form-select" id="payment_no" aria-label="Floating label select example" name="payment_no" >
                                            
                                            @if ($payno == null)
                                                <option selected value="1">1</option>
                                            @elseif ($payno->payment_no >= 1 )
                                                <option selected value="{{ $payno->payment_no + 1 }}">{{ $payno->payment_no + 1 }}</option>
                                            @endif

                                        </select>
                                        <label for="floatingSelect">Payment No.</label>
                                    </div>




                                    <br>

                                    <div class="form-floating">
                                        
                                        <input oninput="" type="text" class="form-control form-control-sm" id="amount" name="amount" placeholder="Amount to Apply" value="{{ $data->payment_term_result }}">
                                        <label for="floatingInput">Amount</label>
                                    </div>
                                    <br>

                                    <div class="input-group date" id="datepicker">
                                        <input type="text" class="form-control" name="payment_date" data-date-format="yy/mm/dd" placeholder="Date">
                                        <span class="input-group-append">
                                        <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                        </span>
                                        </span>
                                    </div>
                                    <br>



                                        <div class="form-floating">
                                            <select class="form-select" id="paid_status" aria-label="Floating label select example" name="paid_status" >
                                                <option selected value="">Select</option>
                                                <option value="Unpaid">Unpaid</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Overdue">Overdue</option>
    
                                            </select>
                                            <label for="floatingSelect">Paid Status</label>
                                        </div>

                                    <br>

                                    <div class="function__button">
                                        <button type="submit" class="btn btn-outline-primary" value="Save">Save</button>
                                        <a href="{{ route('admin.LoanManagement') }}" class="btn btn-outline-dark"><i class='bx bx-arrow-back'></i>Return</a>
                                    </div>

                                    
                                </div> --}}


                                <div class="form-floating client__info-section2">

                                    @if (session('status'))
                                        <h6 class="alert alert-danger">{{ session('status') }}</h6>
                                    @endif
                                   
                                    <div class="form-floating d-flex justify-content-start">
                                        <input type="text" class="form-control form-control-sm" readonly="true" id="overdue" value="{{ $overdue }}" name="overdue" placeholder="Overdue">
                                        <label for="floatingInput">Overdue</label>
                                        
                                    </div>
                                    
                                    <br>
                                    {{-- @if ($loanPid->paid_status != null) --}}
                                        
                                        <div class="form-floating d-flex justify-content-start">
                                            <input type="number" class="form-control form-control-sm" readonly="true" id="totalPenalty" value="{{ $totalPenalty }}" name="totalPenalty" placeholder="Total Penalty">
                                            <label for="floatingInput">Total Penalty</label>  
                                        </div>
                                        
                                        <br>
                                    {{-- @endif --}}

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" readonly="true" id="next_payment_due" name="next_payment_due" value="{{ $nextdue }}" placeholder="Next Payment Due">
                                        <label for="floatingInput">Next Payment Due</label>
                                    </div>
                                    <br>

                                    

                                    {{-- <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" readonly="true" id="date_of_payment" name="date_of_payment" value="" placeholder="Date Of Payment">
                                        <label for="floatingInput">Date Of Payment</label>
                                    </div> --}}
                                    {{-- <div class="input-group date" id="datepicker">
                                        <input required type="text" class="form-control" id="payment_date" name="payment_date" data-date-format="yy/mm/dd" placeholder="Date">
                                        <span class="input-group-append">
                                        <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                        </span>
                                        </span>
                                    </div> --}}
                                    Date of Payment
                                    <div class="input-group date">
                                        <input onkeydown="return false" required type="date" class="form-control" id="payment_date" name="payment_date" data-date-format="yy/mm/dd" placeholder="Date">
                                        <span class="input-group-append">
                                        {{-- <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i> --}}
                                        </span>
                                        </span>
                                    </div>

                                    

                                    <div class="custom-control custom-checkbox pt-3 d-flex justify-content-start">
                                        <input type="checkbox" class="custom-control-input" id="overdue_check" name="overdue_check">
                                        <label id="overdue_label" class="custom-control-label px-3" for="customCheck1">Pay Overdue</label>
                                        <input type="checkbox" class="custom-control-input" id="due_check" name="due_check">
                                        <label id="overdue_label" class="custom-control-label px-3" for="customCheck1">Pay Next Due Payment</label>
                                    </div>
                                    
                                    <div>
                                    
                                    <br>

                                    <div class="function__button">
                                        
                                        
                                        <button type="submit" class="btn btn-outline-success">Pay</button>
                                        <a href="{{ route('admin.LoanManagement') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                                        
                                    </div>

                                </div>


                                



                            </div>
                        </div>
                        
                        
                        
                    </div>
               </form>
            </div> 
        </div>

        {{-- <script>

            var overdue = document.getElementById("overdue").value

            if (overdue == 0) {
                document.FormName.overdue_check.style.display = 'none';
                document.getElementById("overdue_label").style.display = 'none';
            }else{
                document.FormName.overdue_check.style.display = 'block';
                document.getElementById("overdue_label").style.display = 'block';
            }

        </script> --}}

        <script language="javascript">
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
        
            today = yyyy + '-' + mm + '-' + dd;
            $('#payment_date').attr('min',today);
        </script>

        <script>
            $(document).ready(function() { document.getElementById("totalPenalty").value = parseFloat(totalPenalty.value).toFixed(2); });
        
        </script>
@endsection