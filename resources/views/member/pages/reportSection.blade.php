@extends('member.layouts.app2')
@section('content')


<style>

    .card_custom{
        height: auto;
        width: 100%;
        background-color: white;
     }
    
    .linku{
        color:#073b3a;
    }

    .linku:hover{
        color:#a4caca;
    }

    
    </style>
    
      <div class="page__container pt-4">
        <div class="card_custom">
            <div class="card-header">
                <div class="card-header-text">
                    <span><strong style="color: #073b3a">SEND A REPORT</strong></span>
                </div>
                <div class="card-header-btn">
                    
                </div>
            </div>
            <div class="card-body">

                <form action="{{ url('member/sendReport/') }}" method="POST" enctype="multipart/form-data">    
                    @csrf
                        @if(session()->has('status'))
                            <div style="width: 100%; text-align:center;" class="alert alert-success d-flex justify-content-center">
                                {{ session()->get('status') }}
                            </div>
                        @endif
                    <div class="input-group">
                        <textarea name="report" class="form-control" aria-label="With textarea"></textarea>
                    </div>
                    
                    <div class="function__button d-flex justify-content-center mt-2">
                        <button type="submit" class="btn btn-outline-success">Report</button>
                        <a href="{{ route('member.dashboard') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a>
                    </div>
                </form>
                
            </div>
            @if ($find_report->isEmpty() == false)
            <div class="card-body">
                <div class="card-body--header" >
                    
                
                </div>
                
                <div style="overflow-x:auto;" class="table-data">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr style="text-align: center;">
                                <th scope="col">Id</th>
                                <th scope="col">Report Description</th>
                            </tr>
                        </thead>
                        
                            
                        <tbody>
                            @foreach($find_report as $data)
                                <tr style="text-align: center;">
                                    <td>{{ $data->id }}</td>
                                    <td style="width: 50%">{{ $data->report_description }}</td>
                                    <td>

                                        <form method="post" action="{{route('member.reports.destroy',$data->id)}}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>

                                    </td>
                                 
                                </tr>
    
                            @endforeach
                        </tbody>
                        
                    </table>
                    <div class="card-body--header--paginate d-flex justify-content-center">
                        @if ($find_report != null)
                        {{ $find_report->links() }}
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection