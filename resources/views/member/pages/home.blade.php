@extends('member.layouts.app2')

@section('content')
<style>
   body{
    background-color: #508f8a;
   }
    
    .card_custom{
        height: auto;
        width: 100%;
        
        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        
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
    
    .signout {
        text-transform: uppercase;
        font-size: 15px;
        color: #073b3a;
        text-decoration: none;
        position: relative;
        display: block;
       }
    
       .linku{
        color:#073b3a;
    }

    .linku:hover{
        color:#a4caca;
    }

    .memName{
            padding-left: 30px; color:#073b3a; font-size: 20px;
        }

        .profilePic{
            height: 80px; width:80px;

        }

    @media (min-width: 320px) and (max-width: 480px) {

        .memName{
            color:#073b3a; font-size: 15px;
            text-align:center;
            display:block;
            margin:auto;   
        }
        #profileHeader{
            display: flex;
            justify-content: center;
        }

        .profilePic{
            text-align:center;
            width:100%;
            display:block;
            margin:auto;
        }

        .card_custom{
        height: auto;
        width: 100%;
        
        background: linear-gradient(145deg, #ffffff, #e6e6e6);  
     }

        tr{
            text-align: center;
            font-size: 12px;
        }

        .loantext{
            font-size: 12px;
        }

        #cardHeader{
            display: flex;
            flex-direction: column;
            text-align: center;
        }

    }

    </style>
    
    @if(session()->has('error'))
        <div style="width: 100%; text-align:center;" class="alert alert-danger d-flex justify-content-center">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session('status'))
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    
    <div class="page__container pt-4">
        <div class="card_custom">
            
            <div id="profileHeader" class="card-header ">
                <div class="card-header-text">
                    <img class="profilePic" style="" src="{{ url('client_photos/' . $select->client_profile_photo) }}" class="img-thumbnail" alt="..." >
                    <label class="memName" style="" for="Name">{{ $select->client_firstname }} {{ $select->client_middlename }}. {{ $select->client_lastname }}</label>
                </div>
                
            </div>


            {{-- <div class="card_custom"> --}}
                <div class="card-header ">
                    <div class="card-header-text">
                        
                        <label class="loantext" style=" color:#073b3a;">Settled Loans:</label>
                    </div>
                    
                </div>

            
            <div class="card-body">
                <div class="card-body--header" >
                    <div class="card-body--header--paginate">
                        {{-- @if ($completedLoans != null)
                        {{ $completedLoans->links() }}
                        @endif --}}
                        
                    </div>
                    
                </div>
                <!-- <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a> -->
                <div class="table-data">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr style="text-align: center;">
                                <th style=" color:#073b3a;" scope="col">Amount</th>
                                <th style=" color:#073b3a;" scope="col">Release Date</th>
                                <th style=" color:#073b3a;" scope="col">Completed Date</th>
                                {{-- <th scope="col">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($completedLoans as $completedLoans)
                                <tr style="text-align: center;">
                                    @if ($completedLoans->me_id == $cua)
                                        <th scope="row">{{ $completedLoans->actual_amount_on_status }}</th>
                                        <td>{{ $completedLoans->status_date ? date('m-d-Y', strtotime($completedLoans->status_date)): '' }}</td>
                                        <td>{{ $completedLoans->updated_at ? date('m-d-Y', strtotime($completedLoans->updated_at)): '' }}</td>
                                    @endif
                                    
                                </tr>
    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        {{-- </div> --}}



        {{-- <div class="card_custom"> --}}
            <div id="cardHeader" class="card-header">
                <div class="card-header-text">
                    <label class="loantext" style=" color:#073b3a;">Current Loan: @if ($loanCheck == 'Applied' )
                        <span class="loantext" style="color: black"><strong>Under Evaluation</strong></span>
                        @elseif ($loanCheck == 'Denied')
                        <span class="loantext" style="color: black"><strong>Denied</strong></span>
                        @elseif ($loanCheck == 'Approved')
                        <span class="loantext" style="color: black"><strong>On Process</strong></span>
                        @elseif ($loanCheck == 'Released')
                        <span class="loantext" style="color: black"><strong>Released</strong></span>
                        @elseif ($loanCheck == 'Delinquent')
                        <span class="loantext" style="color: black"><strong>Delinquent</strong></span>
                        @endif
                        
                    </label>
                </div>
                
                <div class="card-header-text">

                    <label class="loantext" style=" color:#073b3a;">Next Due Date: <span class="loantext" style="color: black"><strong> @if ($dueCheck != null)
                        {{ $nextdue ? date('m-d-Y', strtotime($nextdue)): '' }} 
                    @endif | ₱{{ $nextdueAmount }}</strong></span></label>
                    <label class="loantext" style=" color:#073b3a;">Overdue Amount: <span class="loantext" style="color: black"><strong>{{ $overdue }}</strong></span></label>
        
                </div>

                <div class="card-header-text">

                    <label class="loantext" style=" color:#073b3a;">Payment Term: <span class="loantext" style="color: black"><strong>{{ $paymentTerm }}</strong></span></label>
                    &nbsp; &nbsp;
                    <label class="loantext" style=" color:#073b3a;">Amount: <span class="loantext" style="color: black"><strong>{{ $Amount }}</strong></span></label>
                
                </div>
                
            </div>

        
        <div class="card-body">
            <div class="card-body--header d-flex justify-content-between" >
                {{-- <div class="bg-linku my-2"><a class="linku" href="{{ route('member.downloadPDF') }}">Download Application Form <i style="float: left;" class='bx bx-download bx-sm px-1'></i></a></div> --}}
                {{-- @if ($nextdueAmount != 0)
                    <div class="bg-linku my-2"><a class="linku" href="{{ route('member.receipt') }}">Receipt <i style="float: right;" class='bx bx-receipt bx-sm px-1'></i></a></div>
                @endif --}}
                
                
            </div>

            
            <!-- <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a> -->
            <div class="table-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style=" color:#073b3a;" scope="col">Due Date</th>
                            <th style=" color:#073b3a;" scope="col">Amount</th>
                            <th style=" color:#073b3a;" scope="col">Status</th>
                            {{-- <th scope="col">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentLoans as $data)
                            <tr>
                                {{-- @if ($data->me_id == $idloaninfo) --}}
                                <th scope="row">{{$data->due_date ? date('m-d-Y', strtotime($data->due_date)): '' }}</th>
                                <td>{{ $data->amount }}</td>
                                <td>{{ $data->paid_status }}</td>
                                {{-- @endif --}}
                                {{-- <td class="actions">
                                     
                
                                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre  href="" ><i class='bx bx-dots-horizontal-rounded bx-sm ps-1 mt-2'></i></a>
                                    
                                        <ul class="dropdown-menu">
                                            <li><a href="" class="btn-sm"><i class='bx bx-edit bx-xs py-2'><span class="px-1">Edit</span></i></a></li>
                                            <li><span data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                <a href="#" class="btn-sm" data-bs-toggle="modal" data-bs-target="" data-bs-placement="top" title="Delete"><i class='bx bx-trash bx-xs py-2'><span class="px-1">Delete</span></i></a>
                                            </span></li>
                                            <li><a href="" class="btn-sm"><i class='bx bx-paperclip bx-xs py-2'><span class="px-1">Attachment</span></i></a></li>
                                            <li><a href="" class="btn-sm"><i class='bx bxs-user-badge bx-xs py-2'><span class="px-1">Link User Account</span></i></a></li>
                                            <li><a href="" class="btn-sm"><i class='bx bxs-user-badge bx-xs py-2'><span class="px-1">Register User Account</span></i></a></li>
                                        </ul>


                                </td> --}}
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                <div class="card-body--header--paginate d-flex justify-content-center">
                    @if ($currentLoans != null)
                    {{ $currentLoans->links() }}
                    @endif
                </div>
            </div>
        </div>
        
    </div>



    </div>
@endsection
