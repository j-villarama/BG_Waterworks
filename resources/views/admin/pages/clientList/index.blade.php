@extends('admin.layouts.app')
@section('content')

<style>

.card_custom{
    height: auto;
    width: auto;
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
    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <span><strong style="color: #073b3a">MEMBERS LIST</strong></span>
            </div>
            <div class="card-header-btn">
                <a href="{{ route('admin.add_client_page') }}" class="btn btn-lg"><i style="color:#073b3a" class="fa fa-user-plus" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body--header" >
                
                <div class="card-body--header--search">
                    {{-- <i class="fa fa-search"></i> --}}
                    <input style="width: 30%;" type="search" class="form-control" placeholder="Search..." id="search" name="search">
                </div>
            </div>
            <!-- <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a> -->
            <div style="overflow-x:auto;" class="table-data">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr style="text-align: center;">
                            <th scope="col">Account Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Contact Number</th>
                            <th scope="col">Member's Name</th>
                            <th scope="col">Birthday</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Address</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($select as $data)
                            <tr style="text-align: center;">
                                <td>{{ $data->account_number }}</td>
                                <td>{{ $data->status }}</td>
                                <td>{{ $data->contact_number }}</td>
                                <td name="fullname">{{ $data->client_firstname }} {{ $data->client_lastname }}</td>
                                <td>{{$data->client_birthday ? date('m-d-Y', strtotime($data->client_birthday)): '' }}</td>
                                <td>{{ $data->client_gender }}</td>
                                <td>{{ $data->client_adress }}</td>
                                <td class="actions">
                                     
                
                                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre  href="" ><i style="color:#073b3a;" class='bx bx-dots-horizontal-rounded bx-sm ps-1 mt-2'></i></a>
                                    
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('admin.edit_client_data',$data->id) }}" class="btn-sm"><i class='bx bx-edit bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Edit</span></i></a></li>
                                            <li><span data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                <a href="#" class="btn-sm" data-bs-toggle="modal" data-bs-target="#DeleteClient_{{ $data->id }}" data-bs-placement="top" title="Delete"><i class='bx bx-trash bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Delete</span></i></a>
                                            </span></li>
                                            {{-- <li><a href="{{ route('admin.attachment', $data->id) }}" class="btn-sm"><i class='bx bx-paperclip bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Attachment</span></i></a></li> --}}
                                            {{-- <li><a href="{{ url('admin/create_user/'.$data->id) }}" class="btn-sm"><i class='bx bxs-user-badge bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Link User Account</span></i></a></li> --}}
                                            {{-- <li><a href="{{ route('register') }}" class="btn-sm"><i class='bx bxs-user-badge bx-xs py-2'><span class="px-1" style="font-family: sans-serif">Register User Account</span></i></a></li> --}}
                                        </ul>


                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="DeleteClient_{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong>{{ $data->client_lastname }}, {{ $data->client_firstname }}</strong> record?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.delete_client',$data->id) }}" method="POST" class="del__button">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes</a>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal End -->

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
