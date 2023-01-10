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
                        <strong style="color: #073b3a">Release To Customer</strong> 
                    </button>
                    
    
                    
    
               </nav>
               <form name="FormName" action="{{ url('admin/release_update/'.$data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane show active fade" id="tab-client-info">
                            <div class="tab__content-flex d-flex">
                                <div class="form-floating client__info-section1">
                                    @if (session('status'))
                                        <h6 class="alert alert-success">{{ session('status') }}</h6>
                                    @endif
                                    

                                    <br>
                                    <div class="form-floating">
                                        <input type="hidden" class="form-control form-control-sm" id="interest_rate" name="interest_rate" placeholder="" value="{{ $data->interest_rate }}">
                                        <input oninput="" type="text" class="form-control form-control-sm" id="amount_apply" name="amount_apply" placeholder="Amount to Apply" value="{{ $data->amount_approved }}">
                                        <label for="floatingInput">Amount to Apply</label>
                                    </div>
                                    <br>

                                    
                                    

                                    <div class="form-floating">
                                        <select onclick="unselect()" class="form-select" id="months" aria-label="Floating label select example" name="months" >
                                            <option selected value="{{ $data->contract_no_months }}">{{ $data->contract_no_months }}</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        <label for="floatingSelect">Contract:(No. Of Months)</label>
                                    </div>
                                    <br>

                                    <div class="function__button d-flex justify-content-start">
                                        <a href="#" onclick="compute()" class="btn btn-outline-dark">Check Monthly Breakdown</a>
                                    </div>

                                    <div class="function__button ">
                                        <button type="submit" class="btn btn-outline-success" value="Save">Save</button>
                                        <a href="{{ route('admin.LoanManagement') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Return</a>
                                    </div>

                                    
                                </div>


                                <div class="client__info-section2">
                                    
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="shared_capital" name="" placeholder="Shared Capital">
                                        <label for="floatingInput">Shared Capital</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="savings_deposit" name="" placeholder="Savings Deposit">
                                        <label for="floatingInput">Savings Deposit</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="interest" name="" placeholder="Interest">
                                        <label for="floatingInput">Interest</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="service_fee" name="" placeholder="Service Fee">
                                        <label for="floatingInput">Service Fee</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="miscellaneous_fee" name="" placeholder="Miscellaneous Fee">
                                        <label for="floatingInput">Miscellaneous Fee</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="payment_perweek" name="" placeholder="Payment Per Week">
                                        <label for="floatingInput">Payment Per Week</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" disabled id="net_proceeds" name="" placeholder="Net Proceeds">
                                        <label for="floatingInput">Net Proceeds</label>
                                    </div>
                                    <br>
                                   
                                </div>

                            </div>
                        </div>
                        
                        
                        
                    </div>
               </form>
            </div> 
        </div>



        <script>

            function compute(){

                var ir = document.getElementById("interest_rate").value;
                var amount = document.getElementById("amount_apply").value;
                var months = document.getElementById("months").value;
                

                var sc = document.getElementById("shared_capital").value = amount * 0.05;
                var sd = document.getElementById("savings_deposit").value = amount * 0.016;
                var interest = document.getElementById("interest").value = ((amount * ir)/12)*months;
                var sf = document.getElementById("service_fee").value = amount * 0.025;
                var mf = document.getElementById("miscellaneous_fee").value = amount * 0.005;
                var ppf = document.getElementById("payment_perweek").value = amount / (months*4);
                document.getElementById("net_proceeds").value = amount - (sc+sd+interest+sf+mf);




                
            }

            function unselect(){

                var amount = document.getElementById("amount_apply").value;
                

                    if ( amount < 6000 ){
                        document.FormName.months.options[1].style.display = 'block';
                        document.FormName.months.options[2].style.display = 'block';
                        document.FormName.months.options[3].style.display = 'block';
                        document.FormName.months.options[4].style.display = 'none';
                        document.FormName.months.options[5].style.display = 'none';
                        document.FormName.months.options[6].style.display = 'none';
                        document.FormName.months.options[7].style.display = 'none';
                        document.FormName.months.options[8].style.display = 'none';
                        document.FormName.months.options[9].style.display = 'none';
                        document.FormName.months.options[10].style.display = 'none';
                        document.FormName.months.options[11].style.display = 'none';
                        
                    }


                    else if( amount <= 8999 ){
                        document.FormName.months.options[1].style.display = 'none';
                        document.FormName.months.options[2].style.display = 'none';
                        document.FormName.months.options[3].style.display = 'none';
                        document.FormName.months.options[4].style.display = 'block';
                        document.FormName.months.options[5].style.display = 'block';
                        document.FormName.months.options[6].style.display = 'none';
                        document.FormName.months.options[7].style.display = 'none';
                        document.FormName.months.options[8].style.display = 'none';
                        document.FormName.months.options[9].style.display = 'none';
                        document.FormName.months.options[10].style.display = 'none';
                        document.FormName.months.options[11].style.display = 'none';
                    }

                    else if( amount <= 9999 ){
                        document.FormName.months.options[1].style.display = 'none';
                        document.FormName.months.options[2].style.display = 'none';
                        document.FormName.months.options[3].style.display = 'none';
                        document.FormName.months.options[4].style.display = 'none';
                        document.FormName.months.options[5].style.display = 'none';
                        document.FormName.months.options[6].style.display = 'block';
                        document.FormName.months.options[7].style.display = 'block';
                        document.FormName.months.options[8].style.display = 'none';
                        document.FormName.months.options[9].style.display = 'none';
                        document.FormName.months.options[10].style.display = 'none';
                        document.FormName.months.options[11].style.display = 'none';
                        
                    }
                    
                    else if( amount >= 10000 ){
                        document.FormName.months.options[1].style.display = 'none';
                        document.FormName.months.options[2].style.display = 'none';
                        document.FormName.months.options[3].style.display = 'none';
                        document.FormName.months.options[4].style.display = 'none';
                        document.FormName.months.options[5].style.display = 'none';
                        document.FormName.months.options[6].style.display = 'none';
                        document.FormName.months.options[7].style.display = 'none';
                        document.FormName.months.options[8].style.display = 'none';
                        document.FormName.months.options[9].style.display = 'none';
                        document.FormName.months.options[10].style.display = 'none';
                        document.FormName.months.options[11].style.display = 'block';

                    }else{
                        document.FormName.months.options[1].style.display = 'block';
                        document.FormName.months.options[2].style.display = 'block';
                        document.FormName.months.options[3].style.display = 'block';
                        document.FormName.months.options[4].style.display = 'block';
                        document.FormName.months.options[5].style.display = 'block';
                        document.FormName.months.options[6].style.display = 'block';
                        document.FormName.months.options[7].style.display = 'block';
                        document.FormName.months.options[8].style.display = 'block';
                        document.FormName.months.options[9].style.display = 'block';
                        document.FormName.months.options[10].style.display = 'block';
                        document.FormName.months.options[11].style.display = 'block';
                    }


                    
                    
                
            }
            
            



        </script>
@endsection