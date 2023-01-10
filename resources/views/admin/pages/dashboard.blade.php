@extends('admin.layouts.app')
@section('content')

<style>
 .card{
    width: 250px;
    height: 150px;
 }

 #card1{
    float:right;
    color: rgb(13, 116, 175);
    font-size: 2.5em;
 }
 #card2{
    color: white; float:right;
 }
 #card3{
    color: white; float:right;
 }
 #card4{
    color: white; float:right;
 }

 #card-line1{
    max-width: 18rem; border-top: 5px solid rgb(13, 116, 175);
 }

 #card-line2{
    max-width: 18rem; border-top: 5px solid rgb(0, 129, 58);
 }

 #card-line3{
    max-width: 18rem; border-top: 5px solid rgb(255, 252, 56);
 }

 #card-line4{
    max-width: 18rem; border-top: 5px solid rgb(255, 63, 56);
 }

 #custom_button{
    border-radius: 0px;
    width: 100%;
 }
 .graph-div{
    display:none;
    width: 100%;
 }

 #card-1{
    display: flex;
}

.welcome{
    color: #073b3a;
}

#int_selection{
    width: 30%;
}
.dropdown-select-int{
    display: flex;
    justify-content: center;
}

@media (min-width: 360px) {

.card_custom{
    height: 100%;
    width: 100%;
    background-color: white;
 }

 #all-body{
    display:grid;
    justify-content:space-evenly;
}

.card-header{
    color: #073b3a;
    text-align: center;
    display: flex;
}

#int_selection{
    width: 45%;
}

.graph-div{
    display:none;
    width: 87%;
 }

}

@media (min-width: 992px) {

#all-body{
    display: flex;
    justify-content:space-evenly;
}

.card_custom{
    height: 100%;
    width: 100%;
    background-color: white;
 }

 #int_selection{
    width: 30%;   
}
.dropdown-select-int{
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
        <div class="card-header">
            
            <span><strong class="welcome">WELCOME, ADMIN!</strong></span>
            
        </div>
        
        <div id="all-body" class="card-body">

            <div id="card-line2" class="card text-white bg-dark">
                
                    <div class="card-body">
                        <h5 class="card-title">MEMBERS <i style="color: rgb(0, 129, 58);" id="card2" class='bx bxs-user-detail bx-lg'></i></h5>
                    
                        <h6>Total: {{ $total_borrowers }}</h6>
                        <a href="{{ route('admin.borrowers.list') }}" id="custom_button" type="button" class="btn btn-light btn-sm mt-4">View Details<i class='bx bx-chevron-right'></i></a>
                </div>
            </div>

            <div id="card-line1" class="card text-white bg-dark">
                
                    <div id="body-card1" class="card-body">
                        <h5 id="card-1" class="card-title">LOAN APPLICATIONS<i id="card1" class='bx bxs-wallet-alt'></i></h5>
                    
                        <h6>Total: {{ $total_loans }}</h6>
                        <a href="{{ route('admin.loan_applicants') }}" id="custom_button" type="button" class="btn btn-light btn-sm mt-2">View Details<i class='bx bx-chevron-right'></i></a>
                </div>
            </div>

            <div id="card-line3" class="card text-white bg-dark">
                
                <div class="card-body">
                    <h5 class="card-title">ACTIVE LOANS <i style="color: rgb(255, 252, 56);" id="card3" class='bx bx-money-withdraw bx-lg'></i></h5>
                    
                    <h6>Total: {{ $total_active_loans }}</h6>
                    <a href="{{ route('admin.LoanManagement') }}" id="custom_button" type="button" class="btn btn-light btn-sm mt-4">View Details<i class='bx bx-chevron-right'></i></a>
                </div>
            </div>

            <div id="card-line4" class="card text-white bg-dark">
                
                <div class="card-body">
                <h5 class="card-title">OVERDUE <i style="color: rgb(255, 63, 56);" id="card4" class='bx bx-calendar-exclamation bx-lg'></i></h5>
                
                <h6>Total: {{ $total_overdue }}</h6>
                <a href="{{ route('admin.loan_overdue') }}" id="custom_button" type="button" class="btn btn-light btn-sm mt-4">View Details<i class='bx bx-chevron-right'></i></a>
            </div>
        </div>

        
            
        </div>

        
    <!-- MONTHLY -->
    @if (!empty($mos))        
        
        @foreach ($mos as $mosi_data)
            
            @php
                
                $items[] = $mosi_data->updated_at->format('M-Y');
                $uniqMos = array_unique($items);
            @endphp
            
        @endforeach

        @if(!empty($uniqMos))
            @foreach ($uniqMos as $gg)
                @php
                    $final_mos[] = $gg;
                    $mos_final_data = array_unique($final_mos);
                @endphp
            @endforeach
        @endif
    @endif

    
    <!-- WEEKLY -->
    @if (!empty($week)) 
        @foreach ($week as $week_data)
            @php
                $week_items[] = $week_data->updated_at->format('M - d -Y');
                $unique_week = array_unique($week_items);
            @endphp
            
        @endforeach

        @if (!empty($unique_week))
            @foreach ($unique_week as $unique_week_sorted)
                @php
                    $final_week_array[] = $unique_week_sorted;
                    $final_week_data = array_unique($final_week_array);
                @endphp
            @endforeach
        @endif
    @endif

    <!-- DAILY -->
    @if (!empty($daily))
        @foreach ($daily as $daily_data)
            @php
                $daily_items[] = $daily_data->updated_at->format('M - d -Y');
                $unique_day = array_unique($daily_items);
            @endphp          
        @endforeach

        @if(!empty($unique_day))
            @foreach ($unique_day as $unique_day_sorted)
                @php
                    $final_day_array[] = $unique_day_sorted;
                    $final_day_data = array_unique($final_day_array);
                @endphp
            @endforeach
        @endif
    @endif
           
        
        <div class="card-body">
            
            
            <h1 style="text-align: center;">Members Loan Overview </h1>
            
            <div class="dropdown-select-int">
                <select onchange="totalbalance(),totalcollected()" class="form-select mr-5" aria-label="Default select example" id="int_selection" name="selectionss">
                    <option value="Monthly" selected>Monthly</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Daily">Daily</option>
                </select>
            </div>
            <div id="container-Monthly" class="graph-div"></div>
            <div id="container-Weekly" class="graph-div"></div>
            <div id="container-Daily" class="graph-div"></div>
            @if (empty($uniqMos))
                <h5 class="mt-5" style="color: red; text-align:center;">No Data Available!</h5>
            @elseif (empty($unique_week))
                <h5 class="mt-5" style="color: red; text-align:center;">No Data Available!</h5>
            @elseif (empty($unique_day)) 
                <h5 class="mt-5" style="color: red; text-align:center;">No Data Available!</h5>
            @endif
            
            <div class="d-flex justify-content-center" onload="onloadbalance()">
                <div class="client__info-section1">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-sm" readonly="true" id="total_balance" name="" placeholder="Total balance">
                        <label for="floatingInput">Total balance left & Amount to be collected</label>
                    </div>
                </div>
                <div class="client__info-section2">    
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-sm" readonly="true" id="total_amount_collected" name="total_amount_collected" placeholder="Total amount collected">
                        <label for="floatingInput">Total amount collected</label>
                    </div>
                    
                </div>
            </div>

        </div>

        {{-- <div class="card-body mt-5">
            <h1 style="text-align: center;">Inspect Member</h1>
            <div class="page__container pt-4">
                <div class="card_custom">
                    <div class="card-header mx-4 my-4">
                        <div class="card-header-text">
                            <span><strong style="color: #073b3a">INSPECT MEMBERS</strong></span>
                        </div>
                    </div>
                    <div class="card-body mx-2 my-2">
                        
                        <div class="card-body--header" >
                            
                            <div class="card-body--header--search">
                                
                                <input style="width: 30%;" type="search" class="form-control" placeholder="Search..." id="searchToInpect" name="searchToInpect">
                            </div>
                        </div>
                        <div class="client-data-inspect">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr style="text-align: center;">
                                        <th scope="col">ID</th>
                                        <th scope="col">Account Number</th>
                                        <th scope="col">Member's Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client_to_inspect as $client_to_inspect_data)
                                        <tr style="text-align: center;">
                                            <td>{{ $client_to_inspect_data->id }}</td>
                                            <td>{{ $client_to_inspect_data->account_number }}</td>
                                            <td name="fullname">{{ $client_to_inspect_data->client_firstname }} {{ $client_to_inspect_data->client_lastname }}</td>
                                            <td><a href="{{ url('admin/specClient/'.$client_to_inspect_data->id) }}">Inspect</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-body--header--paginate d-flex justify-content-center">
                                @if ($client_to_inspect != null)
                                    {{ $client_to_inspect->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div> --}}

    </div>
</div>



<script>



// $(document).ready(function() { var collect = document.getElementById("total_amount_collected").value = {{ $Tacm }}; 
//     if (collect == null) {
//         document.getElementById("total_amount_collected").value = 0;
//     }
// });

$(document).ready(function() { var collect = document.getElementById("total_amount_collected").value = {{ $Tacm }};});

    // $(document).ready(function() { var collect = document.getElementById("total_amount_collected").value = {{ $Tacm }};document.getElementById("total_balance").value = {{ $Tblm }} - collect;});
    

    function totalcollected() {
        var tbm = document.getElementById("int_selection").value

        if (tbm == 'Monthly') {
            document.getElementById("total_balance").value = {{ $Tblm}} - collect;
            document.getElementById("total_amount_collected").value = {{ $Tacm }};
        }else if(tbm == 'Weekly'){
            document.getElementById("total_balance").value = {{ $Tblw}} - collect;
            document.getElementById("total_amount_collected").value = {{ $Tacw }};
        }else if(tbm == 'Daily'){
            document.getElementById("total_balance").value = {{ $Tbld}} - collect;
            document.getElementById("total_amount_collected").value = {{ $Tacd }};
        }

        
            }
  
</script>


<script>
    
        
    $(document).ready(function() { var bal = document.getElementById("total_balance").value = {{ $Tblm }};});
    
    
    

    function totalbalance() {
        var tbm = document.getElementById("int_selection").value

        if (tbm == 'Monthly') {
            document.getElementById("total_balance").value = {{ $Tblm - $Tacm }};
            // document.getElementById("total_amount_collected").value = {{ $Tacm }};
        }else if(tbm == 'Weekly'){
            document.getElementById("total_balance").value = {{ $Tblw - $Tacw }};
            // document.getElementById("total_amount_collected").value = {{ $Tacw }};
        }else if(tbm == 'Daily'){
            document.getElementById("total_balance").value = {{ $Tbld - $Tacd }};
            // document.getElementById("total_amount_collected").value = {{ $Tacd }};
        }
            }
    
        
</script>

<script>

    var collect = document.getElementById("total_amount_collected").value =  {{ $Tacm }};
    

        
            $(document).ready(function() { var bal = document.getElementById("total_balance").value = {{ $Tblm }} - collect });
        
    </script>
    



<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var Mcost = <?php echo json_encode($Mcost)?>;
    var mos = <?php if(!empty($mos_final_data)) {
        echo json_encode($mos_final_data);
    }
    ?>;

    Highcharts.chart('container-Monthly', {
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            // categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            //     'October', 'November', 'December'
            // ]
            // type: 'datetime',
            // tickInterval: 1000 * 3600 * 24 *30 // 1 month
            // categories: ['November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
            //     'August', 'September', 'October'
            // ]
            categories: mos
            
        },
        yAxis: {
            title: {
                text: 'Total Cost'
            }

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
                name: 'Monthly Cost',
                data: Mcost
                
                
            }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>

<!-- WEEKLY GRAPH -->

<script type="text/javascript">
    var cost_text = <?php echo json_encode($Wcost)?>;
    var weekly = <?php if (!empty($final_week_data)) {
        echo json_encode($final_week_data);
    }
    ?>;

    Highcharts.chart('container-Weekly', {
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            // categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            //     'October', 'November', 'December'
            // ]
            // type: 'datetime',
            // tickInterval: 1000 * 3600 * 24 *30 // 1 month
            categories: weekly
            
        },
        yAxis: {
            title: {
                text: 'Total Cost'
            }

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{

                color: '#FFA500',
                name: 'Weekly Cost',
                data: cost_text
                
                
            }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>

<!-- DAILY -->
<script type="text/javascript">
    var DCost = <?php echo json_encode($Dcost)?>;
    var DailyLabel = <?php if (!empty($final_day_data)) {
        echo json_encode($final_day_data);
    }
    ?>;
    Highcharts.chart('container-Daily', {
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            // categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            //     'October', 'November', 'December'
            // ]
            // type: 'datetime',
            // tickInterval: 1000 * 3600 * 24 *30 // 1 month
            categories: DailyLabel
            
        },
        yAxis: {
            title: {
                text: 'Total Cost'
            }

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{

                color: '#964B00',
                name: 'Daily Cost',
                data: DCost
                
                
            }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>



<script>
    $(document).ready(function(){
        $('#int_selection').on('change', function(){
            var demovalue = $(this).val(); 
            $("div.graph-div").hide();
            $("#container-"+demovalue).show();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#container-Monthly").show();
    });
</script>

@endsection