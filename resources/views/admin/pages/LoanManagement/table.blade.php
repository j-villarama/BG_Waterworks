<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Customer Name</th>
            <th scope="col">Amount Of Loan</th>
            <th scope="col">Date Released</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loanlist as $loanlist)
            <tr>
                <td name="fullname">{{ $loanlist->client_firstname }} {{ $loanlist->client_lastname }}</td>
                <td>{{ $loanlist->amount_approved }}</td>
                <td>@if ($loanlist->current_status == 'Released' || $loanlist->current_status == 'Completed' )
                        {{$loanlist->status_date ? date('m-d-Y', strtotime($loanlist->status_date)): '' }}
                @endif
                </td>
                <td name="status">{{ $loanlist->current_status }}</td>
                <td class="actions">
                     

                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre  href="" ><i class='bx bx-dots-horizontal-rounded bx-sm ps-1 mt-2'></i></a>
                    
                        <ul class="dropdown-menu">
                            
                            <li><a href="{{ url('admin/release/'.$loanlist->me_id)}}" class="btn-sm"><i class='bx bx-archive-out bx-xs py-2'><span class="px-1">Release</span></i></a></li>
                            
                            <li><a href="{{ url('admin/delinquent/'.$loanlist->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-x bx-xs py-2'><span class="px-1">Mark As Delinquent</span></i></a></li>
                            
                            <li><a href="{{ url('admin/complete/'.$loanlist->loan_info_id) }}" class="btn-sm"><i class='bx bxs-user-check bx-xs py-2'><span class="px-1">Mark As Complete</span></i></a></li>
                            
                            <li><a href="{{ url('admin/loanpayment/'.$loanlist->loan_info_id) }}" class="btn-sm"><i class='bx bx-money bx-xs py-2'><span class="px-1">Loan Payment</span></i></a></li>
                        
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