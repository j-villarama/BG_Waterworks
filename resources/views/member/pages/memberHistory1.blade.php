@extends('member.layouts.app2')
@section('content')

<style>

    .card_custom{
        height: auto;
        width: 100%;
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
            
            <div style="overflow-x:auto;" class="loan-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Loan No.</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Member Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanlist as $data)
                            <tr>
                                <td>{{ $data->me_id }}</td>
                                <td>{{ $data->account_number }}</td>
                                <td>{{ $data->client_firstname }} {{ $data->client_lastname }}</td>
                                <td><a href="{{ route('member.transactionHistory1',$data->me_id) }}"><b>View Details</b></a></td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                <div class="card-body--header--paginate d-flex justify-content-center">
                    @if ($loanlist != null)
                    {{ $loanlist->links() }}
                    @endif
                </div>
            </div>
        </div>



    </div>
</div>



@endsection
