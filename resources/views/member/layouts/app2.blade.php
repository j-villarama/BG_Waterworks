<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BG Waterworks Multi-Purpose Cooperative</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

        :root {
            --header-height: 3rem;
            --nav-width: 68px;
            --first-color: #073b3a;
            --first-color-light: #3da5d9;
            --white-color: #f1faee;
            --body-font: 'Nunito', sans-serif;
            --normal-font-size: 1rem;
            --z-fixed: 100;
        }

        *,
        ::before,
        ::after {
            box-sizing: border-box
        }

        body {
            position: relative;
            margin: var(--header-height) 0 0 0;
            padding: 0 1rem;
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            transition: .5s
            
            
            
        }

        a {
            text-decoration: none
        }

        .header__sidebar {
            width: 100%;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            background-color: #508f8a;
            z-index: var(--z-fixed);
            transition: .5s
        }

        .header_toggle__sidebar {
            color: white;
            font-size: 1.5rem;
            cursor: pointer
        }

        .header_img__sidebar {
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            border-radius: 50%;
            overflow: hidden
        }

        .header_img__sidebar img {
            width: 40px
        }

        .l-navbar__sidebar {
            position: fixed;
            top: 0;
            left: -30%;
            width: var(--nav-width);
            height: 100vh;
            background-color: var(--first-color);
            padding: .5rem 1rem 0 0;
            transition: .5s;
            z-index: var(--z-fixed)
        }

        .nav__sidebar {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden
        }

        .nav_logo__sidebar,
        .nav_link__sidebar {
            display: grid;
            grid-template-columns: max-content max-content;
            align-items: center;
            column-gap: 1rem;
            padding: .5rem 0 .5rem 1.5rem
        }

        .nav_logo__sidebar {
            margin-bottom: 2rem
        }

        .nav_logo-icon__sidebar {
            font-size: 1.25rem;
            color: var(--white-color)
        }

        .nav_logo-name__sidebar {
            color: var(--white-color);
            font-weight: 700
        }

        .nav_link__sidebar {
            position: relative;
            color: var(--white-color);
            margin-bottom: 1rem;
            transition: .3s
        }

        .nav_link__sidebar:hover {
            /* color: #ffff3f; */
            /* color: var(--white-color); */
            color: #cbcbcc;
        }

        .nav_icon__sidebar {
            font-size: 1.25rem
        }

        .show__sidebar {
            left: 0
        }

        .body-pd {
            padding-left: calc(var(--nav-width) + 1rem)
            
        }

        .active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 2px;
            height: 41px;
            /* background-color: #ffff3f; */
            background-color: var(--white-color);
        }

        .height-100 {
            height: 100vh
        }
        .nav_list__sidebar{
            list-style-type: none;
        }

        .brand{
                color: white; font-weight:600; font-size: 10px;;
        }

        .nav-link{
                font-size: 10px;
            }

        @media screen and (min-width: 768px) {
            body {
                margin: calc(var(--header-height) + 1rem) 0 0 0;
                padding-left: calc(var(--nav-width) + 2rem)
            }

            .header__sidebar {
                height: calc(var(--header-height) + 1rem);
                padding: 0 2rem 0 calc(var(--nav-width) + 2rem)
            }

            .header_img__sidebar {
                width: 40px;
                height: 40px;
            }

            .header_img__sidebar img {
                width: 45px
            }

            .l-navbar__sidebar {
                left: 0;
                padding: 1rem 1rem 0 0
            }

            .show__sidebar {
                width: calc(var(--nav-width) + 156px)
            }

            .body-pd {
                padding-left: calc(var(--nav-width) + 188px)
            }

            .linku{
                color:#073b3a;
            }

            .linku:hover{
                color:#a4caca;
            }

            .brand{
                color: white; font-weight:600; font-size: 15px;
            }
            .nav-link{
                font-size: 15px;
            }
        }
    </style>
</head>
<body id="body-pd" style="background-color:#a4caca;">
    <!-- <nav class="navbar navbar-dark bg-dark sticky-top">
        <a class="navbar-brand" href="#">Navbar</a>
    </nav> -->
    <header class="header__sidebar" id="header__sidebar">
        <div class="header_toggle__sidebar"> <i class='bx bx-menu' id="header-toggle__sidebar"></i> </div>
        
            <div>
               <span class="brand ps-2">BG Waterworks Multi-Purpose Cooperative</span>
            </div>
        {{-- <div class="header_img__sidebar"> <img src="{{URL::asset('/image/BG Waterworks-02.png')}}" alt=""> </div>   --}}
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    
                  
                     
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="font-weight:600;color:white; a:hover">
                        {{ Auth::user()->name }}
                    </a>
                  
                   

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        {{-- <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Sign Out') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form> --}}
                        <a class="dropdown-item" href="#" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#logoutModal" data-bs-placement="top" title="Confirm Sign Out">
                            {{ __('Sign Out') }}
                        </a>




                    </div>
                </li>
            @endguest
        </ul>
              
    </header>
    <div class="l-navbar__sidebar" id="nav-bar__sidebar">
        <nav class="nav__sidebar">
            <div> 
                {{-- <a href="#" class="nav_link__sidebar"> <i class='bx bx-home'></i> <span style="color: white"><strong style="color: #ff002b">B</strong><strong style="color: #5c95ff">G</strong> Waterworks</span> </a> --}}
                <img src="{{URL::asset('/image/BG Waterworks-02.png')}}" alt="" class="img-fluid" style="background: transparent; border:none; padding-left:14px; padding-bottom:20px;">
                <div class="nav_list__sidebar">
                    <li class="{{'member/dashboard' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.dashboard') }}" class="nav_link__sidebar"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name__sidebar">Dashboard</span> </a> 
                    </li>
                    <li class="{{'member/apply_loan' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.apply_loan') }}" class="nav_link__sidebar"> <i class='bx bxs-bank'></i> <span class="nav_name">Apply Loan</span> </a> 
                    </li>
                    <li class="">
                        <a href="{{ route('member.downloadPDF') }}" class="nav_link__sidebar"> <i class='bx bx-download'></i> <span class="nav_name">Application Form</span> </a> 
                    </li>
                    <li class="{{'member/membHistory' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.membHistory') }}" class="nav_link__sidebar"> <i class='bx bx-credit-card'></i> <span class="nav_name">Payment Summary</span> </a> 
                    </li>
                    <li class="{{'member/membHistory1' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.membHistory1') }}" class="nav_link__sidebar"> <i class='bx bx-history'></i> <span class="nav_name">Loan History</span> </a> 
                    </li>
                    <li class="{{'member/account_Setting' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.account_setting') }}" class="nav_link__sidebar"> <i class='bx bxs-cog'></i> <span class="nav_name">Account Setting</span> </a> 
                    </li>
                    {{-- <li class="{{'member/faqs' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.faqs') }}" class="nav_link__sidebar"> <i class='bx bx-question-mark'></i> <span class="nav_name">FAQS</span> </a> 
                    </li> --}}
                    <li class="{{'member/reports' == request()->path() ? 'active' : ''}}">
                        <a href="{{ route('member.reports') }}" class="nav_link__sidebar"> <i class='bx bxs-bug'></i> <span class="nav_name">Report</span> </a> 
                    </li>
                   
                    
                    
                    {{-- <a href="#" class="nav_link__sidebar"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name__sidebar">Messages</span> </a> 
                    <a href="#" class="nav_link__sidebar"> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name__sidebar">Bookmark</span> </a> <a href="#" class="nav_link__sidebar"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a> 
                    <a href="#" class="nav_link__sidebar"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name__sidebar">Stats</span> </a> </div>
                    --}}
                    <!-- <a href="{{ route('logout') }}" class="nav_link__sidebar" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out nav_icon'></i> <span class="nav_name__sidebar">SignOut</span> </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form> -->
                </div>
        </nav>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign Out Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to sign out?
            </div>
            <div class="modal-footer">
                <form action="{{ route('logout') }}" method="POST" class="del__button">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes</a>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
    @yield('content')
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>
<script src="{{ asset('js/app.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: 'yy/mm/dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<script type="text/javascript">
    $('#search').on('keyup',function(){
        $value = $(this).val();
        $.ajax({
            url:"{{ route('admin.search')}}",
            method:'GET',
            data: {'search':$value},

            success:function(data){
                console.log(data);
                $('.table-data').html(data);
            }
        });
    })
</script>

<script type="text/javascript">
    $('#searchloan').on('keyup',function(){
        $value = $(this).val();
        $.ajax({
            url:"{{ route('admin.search.loan')}}",
            method:'GET',
            data: {'searchloan':$value},

            success:function(data){
                console.log(data);
                $('.loan-data').html(data);
            }
        });
    })
</script>

<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
   
   const showNavbar = (toggleId, navId, bodyId, headerId) =>{
   const toggle = document.getElementById(toggleId),
   nav = document.getElementById(navId),
   bodypd = document.getElementById(bodyId),
   headerpd = document.getElementById(headerId)
   
   nav.classList.toggle('show__sidebar')
   toggle.classList.toggle('bx-x')
   bodypd.classList.toggle('body-pd')
   headerpd.classList.toggle('body-pd')

   // Validate that all variables exist
   if(toggle && nav && bodypd && headerpd){
   toggle.addEventListener('click', ()=>{
   // show navbar
   nav.classList.toggle('show__sidebar')
   // change icon
   toggle.classList.toggle('bx-x')
   // add padding to body
   bodypd.classList.toggle('body-pd')
   // add padding to header
   headerpd.classList.toggle('body-pd')
   })
   }
   }
   
   showNavbar('header-toggle__sidebar','nav-bar__sidebar','body-pd','header__sidebar')
   
   /*===== LINK ACTIVE =====*/
   const linkColor = document.querySelectorAll('.nav_link__sidebar')
   
   function colorLink(){
   if(linkColor){
   linkColor.forEach(l=> l.classList.remove('active'))
   this.classList.add('active')
   }
   }
   linkColor.forEach(l=> l.addEventListener('click', colorLink))
   
    // Your code to run since DOM is loaded and ready
   });

</script>
</html>