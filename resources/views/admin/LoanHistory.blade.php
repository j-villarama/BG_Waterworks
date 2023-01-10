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
                <span style="color:#073b3a;"><strong>LOAN HISTORY</strong></span>
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
                            <th scope="col">Amount of Loan</th>
                            <th scope="col">Date Released</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reason/Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanlist as $data)
                            <tr>
                                <td>{{ $data->amount_approved }}</td>
                                <td>@if ($data->current_status != 'Applied')
                                    {{$data->status_date ? date('m-d-Y', strtotime($data->status_date)): '' }}
                                @endif  
                                </td>
                                <td>{{ $data->current_status }}</td>
                                <td>
                                @if ($data->current_status == 'Delinquent' || $data->current_status == 'Completed' || $data->current_status == 'Denied')
                                    {{ $data->remarks }}
                                @endif
                                </td>
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
                            <th style=" color:#073b3a;" scope="col">Amount Paid</th>
                            <th style=" color:#073b3a;" scope="col">Maturity Date</th>
                            <th style=" color:#073b3a;" scope="col">Payment Date</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payHistory as $data)
                            <tr>
                                
                                <td>{{ $data->amount }}</td>
                                <td scope="row">{{$data->due_date ? date('m-d-Y', strtotime($data->due_date)): '' }}</td>
                                <td>{{$data->payment_date ? date('m-d-Y', strtotime($data->payment_date)): '' }}</td>
                                
                                
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
