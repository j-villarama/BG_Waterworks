@extends('admin.layouts.app')
@section('content')
 
<style>

    .card_custom{
        height: auto;
        width: 1000px;
        border-radius: 15px;
        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        box-shadow:  20px 20px 60px #d9d9d9,
                    -20px -20px 60px #ffffff;
     }
    
</style>

    
    <select class="form-select" aria-label="Default select example" name="per" id="per">
        {{-- <option selected>Open this select menu</option> --}}
        <option value="weekly" selected>Weekly</option>
        <option value="semi-monthly">Semi-monthly</option>
        <option value="monthly">Monthly</option>
    </select>
    {{-- <h1 class="output">

    </h1> --}}
        <?php 
           
            $length = count($dates);
            for ($i = 0; $i < $length; $i++) {  
                echo $dates[$i] . '<br>';
            }
        ?>
    <script type="text/javascript">
        $("#per").change(function () {
            selectElement = document.querySelector('#per');
            output = selectElement.value;
            document.querySelector('.output').textContent = output;
        });
    </script>
@endsection