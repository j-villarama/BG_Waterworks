@extends('admin.layouts.app')
@section('content')

<style>
   

.card_custom{
    height: auto;
    width: 1000px;
    background-color: white;
    
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


<div class="page__container pt-4">
    <div class="card_custom">
        <div class="card-header">
            <div class="card-header-text">
                <span><strong style="color: #073b3a">REPORTS</strong></span>
            </div>
            
        </div>
        <div class="card-body">
            <div class="card-body--header" >
                
            
            </div>
            
            <div class="table-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr style="text-align: center;">
                            <th scope="col">Report By</th>
                            <th scope="col">Report Description</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($select as $data)
                            <tr style="text-align: center;">
                                <td>{{ $data->report_by }}</td>
                                <td style="width: 90%">{{ $data->report_description }}</td>
                                
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                <div class="card-body--header--paginate d-flex justify-content-center">
                    @if ($select != null)
                    {{ $select->links() }}
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
 

@endsection
