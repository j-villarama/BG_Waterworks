@extends('member.layouts.app2')
@section('content')
 

<style>

    .card_custom{
        height: auto;
        width: 100%;
        background-color: white;
        
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

.tab__content-flex{
    flex-direction: column;
}

.function__button{
    justify-content: center;
}

}
    
    </style>
        @if(session()->has('error'))
        <div style="width: 100%; text-align:center;" class="alert alert-danger d-flex justify-content-center">
            {{ session()->get('error') }}
        </div>
        @endif
        <div class="page__container pt-4">
            
            <div class="card_custom">
               <nav class="nav nav-tabs">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-client-info">
                        <strong style="color: #073b3a">Apply Loan</strong>
                    </button>
                    
    
                    
    
               </nav>
               <form name="FormName" action="{{ route('member.applied_loan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane show active fade" id="tab-client-info">
                            <div class="tab__content-flex d-flex">
                                <div class="form-floating client__info-section1">
                                    @if (session('status'))
                                        <h6 class="alert alert-success">{{ session('status') }}</h6>
                                    @endif
                                    
                                    {{-- <div class="form-floating">
                                        <select required class="form-select" id="selected_name" aria-label="Floating label select example" name="customer_name">
                                            <option selected value="{{ null }}" disabled>Select Customer</option>
                                            @foreach ($name as $key => $name)
                                                <option value="{{ $name->id }}">     
                                                    {{ $name->client_firstname }}, {{ $name->client_lastname }} 
                                                </option>
                                            @endforeach
                                            
                                        </select>
                                        <label for="floatingSelect">Customer Name</label>
                                    </div> --}}

                                    
                                    <div class="form-floating">
                                        <input onchange="compute(),unselect(),digilimits()" onkeyup="compute(),unselect(),digilimits()" onkeydown="compute(),unselect(),digilimits()" minlength="4" maxlength = "5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required oninput="unselect()" type="number" class="removeLetters removeKeys form-control form-control-sm" id="amount_apply" name="amount_apply" placeholder="Amount to Apply" value="0">
                                        <label for="floatingInput">Amount to Apply</label>
                                    </div>
                                    <br>

                                    
                                    {{-- <div class="input-group date" id="datepicker">
                                        <input required type="text" class="form-control" id="status_date" name="status_date" data-date-format="yy/mm/dd" placeholder="Date">
                                        <span class="input-group-append">
                                        <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                        </span>
                                        </span>
                                    </div> --}}

                                    <div class="input-group date">
                                        <input onchange="compute(),digilimits()" onkeydown="return false" required type="date" class="form-control" id="date_picker" name="status_date" data-date-format="yy/mm/dd" placeholder="Date">
                                        <span class="input-group-append">
                                        {{-- <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i> --}}
                                        </span>
                                        </span>
                                    </div>
                                    <br>

                                    {{-- <div class="form-floating">
                                        <input required type="text" class="form-control form-control-sm" id="interest_rate" name="interest_rate" placeholder="Interest Rate per annum">
                                        <label for="floatingInput">Interest Rate per annum</label>
                                    </div>
                                    <br> --}}

                                    <div class="form-floating">
                                        <select onclick="unselect(),digilimits()" onselect="unselect(),digilimits()" onchange="compute(),unselect(),digilimits()" required class="form-select" id="months" aria-label="Floating label select example" name="months">
                                            <option value="{{ null }}" selected></option>
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
                                        <label for="floatingSelect">Loan Term</label>
                                    </div>
                                    <br>


                                    <div class="form-floating">
                                        <select onchange="compute(),digilimits()" required class="form-select" id="payment_term" aria-label="Floating label select example" name="payment_term">
                                            <option value="{{ null }}" selected></option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Semi-Monthly">Semi-Monthly</option>
                                            <option value="Monthly">Monthly</option>

                                            
                                        </select>
                                        <label for="floatingSelect">Payment Term</label>
                                    </div>
                                    <br>


                                    {{-- <div class="function__button">
                                        <a href="#" id="compute" onclick="compute()" class="btn btn-outline-dark">Compute</a>
                                    </div> --}}

                                    

                                </div>


                                <div class="client__info-section2">
                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="shared_capital" name="" placeholder="Shared Capital">
                                        <label for="floatingInput">Shared Capital</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="savings_deposit" name="" placeholder="Savings Deposit">
                                        <label for="floatingInput">Savings Deposit</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="interest" name="" placeholder="Interest">
                                        <label for="floatingInput">Interest</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="service_fee" name="" placeholder="Service Fee">
                                        <label for="floatingInput">Service Fee</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="miscellaneous_fee" name="" placeholder="Miscellaneous Fee">
                                        <label for="floatingInput">Miscellaneous Fee</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" required class="form-control form-control-sm" readonly="true" id="payment_term_result" name="payment_term_result" placeholder="Payment Term">
                                        <label id="ppw" for="floatingInput">Payment Term</label>
                                    </div>
                                    <br>

                                    <div class="form-floating">
                                        <input oninput="digilimits()" type="text" class="form-control form-control-sm" readonly="true" id="net_proceeds" name="" placeholder="Net Proceeds">
                                        <label for="floatingInput">Net Proceeds</label>
                                    </div>
                                    <br>

                                    <div class="col-12 d-flex justify-content-center pb-4">
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                          <label class="form-check-label" for="invalidCheck">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalLong">loan terms and aggreement</a>
                                          </label>
                                          <div class="invalid-feedback">
                                            You must agree before submitting.
                                          </div>
                                        </div>
                                    </div>
                                    <br>
                                    
                                    
                                    <div class="function__button">
                                        <button type="submit" class="btn btn-outline-success" value="Save">Save</button>
                                        <a href="{{ route('admin.LoanManagement') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        
                        
                    </div>
               </form>

               <!-- Modal -->
               <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Loan Terms And Agreement</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    
                        <div>
                            <span><b>SALAYSAY NG NANGHIHIRAM NG PUHUNAN</b></span>
                            <ul>
                                    Ako ay nangangako at nagpapatunay na ang lahat ng sagot sa mga katanungan sa itaas nito ay
                                    totoong lahat at tama. Ako ay lubos na nagpapahintulot at nagbibigay ng lubos na karapatan
                                    sa Balubal-Gibanga Waterworks and Multi-Purpose Cooperative na gumawa ng kaukulang hakbang
                                    o pagsisiyasat kaugnay ng aking pagkatao at kredito at iba pang impormasyon na kinakailangan
                                    ng BGWMPC.
                            </ul>
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
                </div>
            </div>

            </div> 
        </div>


        <script>

            function compute(){

                


                var ir = 0.12;
                var amount = document.getElementById("amount_apply").value;
                var months = document.getElementById("months").value;
                var paymentTerm = document.getElementById("payment_term").value


                var ppw = document.getElementById('ppw');
                var compute = document.getElementById('compute');                

                var sc = document.getElementById("shared_capital").value = amount * 0.05;
                var sd = document.getElementById("savings_deposit").value = amount * 0.016;
                var interest = document.getElementById("interest").value = ((amount * ir)/12)*months;
                var sf = document.getElementById("service_fee").value = amount * 0.025;
                var mf = document.getElementById("miscellaneous_fee").value = amount * 0.005;
                // var ppf = document.getElementById("payment_perweek").value = amount / (months*4);
                document.getElementById("net_proceeds").value = amount - (sc+sd+interest+sf+mf);
                


                if ( paymentTerm == 'Weekly'){
                    

                    
                    var ppf = document.getElementById("payment_term_result").value = amount / (months*4);
                    document.getElementById('ppw').innerHTML = 'Payment';
                    
                    
                    
                    
                }

                else if(paymentTerm == 'Semi-Monthly'){
                    
                    var ppf = document.getElementById("payment_term_result").value = amount / (months*2);
                    document.getElementById('ppw').innerHTML = 'Payment';

                    
                     
                }

                else if(paymentTerm == 'Monthly'){
                    
                    var ppf = document.getElementById("payment_term_result").value = amount / (months);
                    document.getElementById('ppw').innerHTML = 'Payment';

                    

                }

                
                
            }

            function unselect(){

                

                var amount = document.getElementById("amount_apply").value;
                


                if(amount == null || amount == 0 || amount == ""){
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
                        document.FormName.months.options[11].style.display = 'none';
                    }

                    else if ( amount < 6000 ){
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
                        document.FormName.months.options[1].style.display = 'block';
                        document.FormName.months.options[2].style.display = 'block';
                        document.FormName.months.options[3].style.display = 'block';
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
                        document.FormName.months.options[1].style.display = 'block';
                        document.FormName.months.options[2].style.display = 'block';
                        document.FormName.months.options[3].style.display = 'block';
                        document.FormName.months.options[4].style.display = 'block';
                        document.FormName.months.options[5].style.display = 'block';
                        document.FormName.months.options[6].style.display = 'block';
                        document.FormName.months.options[7].style.display = 'block';
                        document.FormName.months.options[8].style.display = 'none';
                        document.FormName.months.options[9].style.display = 'none';
                        document.FormName.months.options[10].style.display = 'none';
                        document.FormName.months.options[11].style.display = 'none';
                        
                    }
                    
                    else if( amount >= 10000 ){
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

<script language="javascript">
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    $('#date_picker').attr('min',today);
</script>

<script>
    document.querySelector(".removeKeys").addEventListener('keydown', function(e) {
    if (e.which === 38 || e.which === 40) {
        e.preventDefault();
    }
});
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
    function digilimits(){
        document.getElementById("shared_capital").value = parseFloat(shared_capital.value).toFixed(2);
        document.getElementById("savings_deposit").value = parseFloat(savings_deposit.value).toFixed(2);
        document.getElementById("interest").value = parseFloat(interest.value).toFixed(2);
        document.getElementById("service_fee").value = parseFloat(service_fee.value).toFixed(2);
        document.getElementById("miscellaneous_fee").value = parseFloat(miscellaneous_fee.value).toFixed(2);
        document.getElementById("payment_term_result").value = parseFloat(payment_term_result.value).toFixed(2);
        document.getElementById("net_proceeds").value = parseFloat(net_proceeds.value).toFixed(2);

    }

</script>


@endsection