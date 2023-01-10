@extends('admin.layouts.app')
@section('content')
<style>

.card_custom{
    height: auto;
    width: 1200px;
    background-color: white;
 }

 .card{
    width: 250px;
    height: 150px;
 }

 #card1{
    color: white; float:right;
 }

 #card1:hover{
    color: #508f8a;
 }

 #custom_button{
    border-radius: 0px;
    width: 100%;
 }
 .spec-graph-div{
    display:none;
 }
</style>
<div class="card-body">
    <!-- LABEL FOR MONTHLY -->
    @foreach ($SpecMosLabel as $SpecMosLabel_data)
                
        @php
            $SpecMosLabel_items[] = $SpecMosLabel_data->updated_at->format('M');
            $uniqSpecMosLabel = array_unique($SpecMosLabel_items);
        @endphp
        
    @endforeach
    @foreach ($uniqSpecMosLabel as $finalMosLabel)
        @php
            $final_uniqSpecMosLabel[] = $finalMosLabel;
            $uniqSpecMosLabel_final_data = array_unique($final_uniqSpecMosLabel);
        @endphp
    @endforeach

    <!-- LABEL FOR WEEKLY -->
    @foreach ($SpecWeekLabel as $SpecWeekLabel_data)
                
        @php
            $SpecWeekLabel_items[] = $SpecWeekLabel_data->updated_at->format('M - d');
            $uniqSpecWeekLabel = array_unique($SpecWeekLabel_items);
        @endphp
        
    @endforeach
    @foreach ($uniqSpecWeekLabel as $finalSpecWeekLabel)
        @php
            $final_uniqSpecWeekLabel[] = $finalSpecWeekLabel;
            $uniqSpecWeekLabel_final_data = array_unique($final_uniqSpecWeekLabel);
        @endphp
    @endforeach

    <!-- LABEL FOR DAILY -->
   
    @foreach ($SpecDailyLabel as $SpecDailyLabel_data)
        @php
            $SpecDailyLabel_items[] = $SpecDailyLabel_data->updated_at->format('M - d');
            $unique_SpecDailyLabel = array_unique($SpecDailyLabel_items);
           
        @endphp
    @endforeach

    @foreach ($unique_SpecDailyLabel as $unique_SpecDailyLabel_sorted)
        
        @php
            $final_SpecDailyLabel_array[] = $unique_SpecDailyLabel_sorted;
            $final_SpecDailyLabel_data = array_unique($final_SpecDailyLabel_array);
        @endphp
        
    @endforeach
    <div class="dropdown-select-int d-flex justify-content-end mb-3 mt-3">
        <select class="form-select mr-5" aria-label="Default select example" style="width: 20%;" id="spec_int_selection">
            <option value="Monthly" selected>Monthly</option>
            <option value="Weekly">Weekly</option>
            <option value="Daily">Daily</option>
        </select>
    </div>
   
    @foreach ($clientName as $client_Name_data)
                
    @endforeach
    <h1 style="text-align: center;">{{ $client_Name_data->client_firstname }} {{ $client_Name_data->client_lastname }}</h1>
    <div id="spec-container-Monthly" class="spec-graph-div"></div>
    <div id="spec-container-Weekly" class="spec-graph-div"></div>
    <div id="spec-container-Daily" class="spec-graph-div"></div>
    
</div>   
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- SPECIFIC MONTHLY -->
<script type="text/javascript">
    var SpecMos = <?php echo json_encode($SpecMos)?>;
    var SpecMosLabel = <?php echo json_encode($uniqSpecMosLabel_final_data)?>;
    Highcharts.chart('spec-container-Monthly' , {
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
            categories: SpecMosLabel
            
        },
        yAxis: {
            title: {
                text: 'Total Loan'
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
            
                
                name: 'Monthly Loan',
                data: SpecMos
                
                
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

<!-- SPECIFIC WEEKLY -->
<script type="text/javascript">
    var SpecWeek = <?php echo json_encode($SpecWeek)?>;
    var SpecWeekLabel = <?php echo json_encode($uniqSpecWeekLabel_final_data)?>;
    Highcharts.chart('spec-container-Weekly' , {
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
            categories: SpecWeekLabel
            
        },
        yAxis: {
            title: {
                text: 'Total Loan'
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
                name: 'Weekly Loan',
                data: SpecWeek
                
                
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

<!-- SPECIFIC DAILY -->
<script type="text/javascript">
    var SpecDay = <?php echo json_encode($SpecDay)?>;
    var SpecDailyLabel = <?php echo json_encode($final_SpecDailyLabel_data)?>;
    Highcharts.chart('spec-container-Daily' , {
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
            categories: SpecDailyLabel
            
        },
        yAxis: {
            title: {
                text: 'Total Loan'
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
                name: 'Daily Loan',
                data: SpecDay
                
                
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
        $('#spec_int_selection').on('change', function(){
            var demovalue = $(this).val(); 
            $("div.spec-graph-div").hide();
            $("#spec-container-"+demovalue).show();
        });
    });
    
</script> 

<script>
    $(document).ready(function() {
        $("#spec-container-Monthly").show();
    });
</script>
@endsection