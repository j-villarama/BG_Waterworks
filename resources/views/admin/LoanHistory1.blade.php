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

     .page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #073b3a; 
    border-color: #073b3a; 
}

.page-item .page-link {
    z-index: 1;
    color: #073b3a;
    
    
}
    
</style>

@if (session('status'))
    <h6 class="alert alert-success">{{ session('status') }}</h6>
@endif
@if(session()->has('error'))
    <div style="width: 100%; text-align:center;" class="alert alert-danger d-flex justify-content-center">
        {{ session()->get('error') }}
    </div>
@endif
<div class="page__container pt-4">
    <div class="card_custom">
        <div class="card-header ">
            <div class="card-header-text">
                <span style="color:#073b3a;"><strong>PAYMENT SUMMARY</strong></span>
            </div>
            <div class="card-header-btn">
                
            </div>
        </div>

        <div class="card-body">
            <div class="card-body--header" >
                
        
            </div>
            
            <div class="loan-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Loan Amount</th>
                            <th scope="col">Loan Term</th>
                            <th scope="col">Loan Payment Term</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanlist as $data)
                            <tr>
                                <td>{{ $data->amount_approved }}</td>
                                <td>{{ $data->contract_no_months }} @if ($data->contract_no_months != null)
                                    Months
                                @endif</td>
                                <td>{{ $data->payment_term }}</td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="card-body--header--paginate d-flex justify-content-center">
                    @if ($loanlist != null)
                    {{ $loanlist->links() }}
                    @endif
                </div> --}}
            </div>
        </div>



        <div class="card-body">
            <div class="card-body--header d-flex justify-content-between" >
   
            </div>

            <div class="table-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style=" color:#073b3a;" scope="col">Due date</th>
                            <th style=" color:#073b3a;" scope="col">Amount</th>
                            <th style=" color:#073b3a;" scope="col">Overdue</th>
                            <th style=" color:#073b3a;" scope="col">Outstanding Balance</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payHistory as $data)
                            <tr>
                                
                                <td>{{ $data->due_date }}</td>
                                <td>{{ $data->amount }}</td>
                                <td>{{ $data->penalty }}</td>
                                <td>{{ $data->amount + $data->penalty }}</td>
                                
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="card-body--header--paginate d-flex justify-content-center">
                    @if ($currentLoans != null)
                    {{ $currentLoans->links() }}
                    @endif
                </div> --}}
            </div>
        </div>

        

    </div>
</div>



@endsection
