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
                <span style="color:#073b3a;"><strong>LOAN OVERDUE</strong></span>
            </div>
            {{-- <div class="card-header-btn">
                <a href="{{ route('admin.create_loan_page') }}" class="btn btn-sm"><i class="bx bx-plus-circle bx-sm" style="color: #073b3a" aria-hidden="true"></i></a>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="card-body--header" >
                
                <div class="card-body--header--search">
                    
                
                    <input style="width: 30%;" type="search" class="form-control" placeholder="Search..." id="searchloan" name="searchloan">
                </div>
            </div>
            
            <div class="loan-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Member's Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date Released</th>
                            <th scope="col">Payment No.</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanlist as $data)
                            <tr>
                                <td name="fullname">{{ $data->client_firstname }} {{ $data->client_lastname }}</td>
                                <td>{{ $data->amount }}</td>
                                <td>@if ($data->current_status == 'Released' || $data->current_status == 'Completed' )
                                    {{$data->status_date ? date('m-d-Y', strtotime($data->status_date)): '' }}
                                @endif
                                </td>
                                <td name="status">{{ $data->payment_no }}</td>
                                <td >{{ $data->paid_status }}</td>
                                <td class="actions">
                                     
                
                                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre  href="" ><i style="color:#073b3a" class='bx bx-dots-horizontal-rounded bx-sm ps-1 mt-2'></i></a>
                                    
                                        <ul class="dropdown-menu">

                                            {{-- <li><a href="{{ url('admin/approved/'.$data->me_id) }}" class="btn-sm"><i class='bx bx-check-circle bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Approved</span></i></a></li>
                                            
            
                                            <li><a href="{{ url('admin/release/'.$data->me_id)}}" class="btn-sm"><i class='bx bx-archive-out bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Release</span></i></a></li> 
                                            
                                            
                                            
                                            <li><a href="{{ url('admin/delinquent/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-x bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Mark As Delinquent</span></i></a></li>
                                            

                                            
                                            <li><a href="{{ url('admin/complete/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-check bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Mark As Complete</span></i></a></li>
                                            

                                            
                                            <li><a href="{{ url('admin/loanpayment/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bx-money bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Loan Payment</span></i></a></li> --}}
                                            
                                            @if ($data->current_status == 'Applied')
                                                <li><a href="{{ url('admin/approved/'.$data->me_id) }}" class="btn-sm"><i class='bx bx-check-circle bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Approved</span></i></a></li>
                                            @endif
                                            
                                            @if ($data->current_status == 'Approved')
                                                <li><a href="{{ url('admin/release/'.$data->me_id)}}" class="btn-sm"><i class='bx bx-archive-out bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Release</span></i></a></li> 
                                            @endif
                                            
                                            
                                            @if ($data->current_status == 'Released')
                                                <li><a href="{{ url('admin/delinquent/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-x bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Mark As Delinquent</span></i></a></li>
                                            @endif
                                            
                                            
                                            @if ($data->current_status == 'Released')
                                                <li><a href="{{ url('admin/complete/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-check bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Mark As Complete</span></i></a></li>
                                            @endif

                                            @if ($data->current_status == 'Released' || $data->current_status == 'Delinquent')
                                            <li><a href="{{ url('admin/loanpayment/'.$data->loan_info_id) }}" class="btn-sm"><i class='bx bx-money bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Loan Payment</span></i></a></li>
                                            @endif

                                            @if ($data->current_status == 'Completed')
                                                <li><span class="px-1" style="font-family: sans-serif;">No available actions</span></li>
                                            @endif

                                        </ul>


                                </td>
                            </tr>

                            {{-- <!-- Modal -->
                            <div class="modal fade" id="DeleteClient_{{ $loanlist->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong>{{ $loanlist->client_lastname }}, {{ $loanlist->client_firstname }}</strong> record?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.delete_client',$loanlist->id) }}" method="POST" class="del__button">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes</a>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal End --> --}}

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
