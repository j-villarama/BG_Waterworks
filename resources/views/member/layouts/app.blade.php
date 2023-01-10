<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BG Waterworks Multi-Purpose Cooperative</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    
</head>
<body>
    <div id="app">
        
        <nav class="navbar navbar-expand-md">
            <div class="container">
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li style="padding: 10px;">
                            <a style="text-transform: uppercase;
                            font-size: 20px;
                            color: azure;
                            text-decoration: none;
                            position: relative;
                            display: block;" href="" data-bs-toggle="modal" data-bs-target="#exampleModalLong" class="nav_link__sidebar"><span class="nav_name">ABOUT</span></a> 
                        </li>
                        <li style="padding: 10px;">
                            <a style="text-transform: uppercase;
                            font-size: 20px;
                            color: azure;
                            text-decoration: none;
                            position: relative;
                            display: block;" href="" data-bs-toggle="modal" data-bs-target="#exampleModalLong1" class="nav_link__sidebar"><span class="nav_name">CONTACT</span></a> 
                        </li>
                        <li style="padding: 10px;">
                            <a style="text-transform: uppercase;
                            font-size: 20px;
                            color: azure;
                            text-decoration: none;
                            position: relative;
                            display: block;" href="" data-bs-toggle="modal" data-bs-target="#exampleModalLong2" class="nav_link__sidebar"><span class="nav_name">FAQS</span></a> 
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="ps-3" style="color: rgb(46, 46, 46)">BG Waterworks Multi-Purpose Cooperative</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <main class="py-4">
            @yield('content')

            <!-- Modal1 -->
            <div style="color: black" class="modal fade" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle1">CONTACT</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    

                        <div>
                            <span><b>EMAIL : </b>Balubal_GibangaWaterworksandMPC@yahoo.com</span>
                            
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
                </div>
            </div>


            <!-- Modal2 -->
            <div style="color: black" class="modal fade" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">FAQS</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    

                        <div>
                            <span><b>1. What is a personal loan?</b></span>
                            <ul>
                                <li>A personal loan is an unsecured installment loan given to the 
                                    borrower as a lump – sum payment. Unsecured simply means the 
                                    loan is not backed by collateral such as home, jewelry, car etc. 
                                    These loans are typically available from a traditional bank, credit 
                                    union and like other installment loans, are paid back in equal 
                                    monthly payments with fixed interest rate.</li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>2. What are personal loans used for?</b></span>
                            <ul>
                                <li>Personal loans can be used all sorts of expenses, like debt
                                    consolidation, home improvement, auto expense, medical 
                                    expenses etc. It will always depend to the borrower desire on what 
                                    will do in their loan.</li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>3. Am I eligible for a personal loan? What documents are needed for a 
                                personal loan?</b></span>
                            <ul>
                                <li>Since there`s no collateral, qualifying for personal loan is ultimately 
                                    determined by your credit history, income, other debt obligation. 
                                    Furthermore the main reason of Balubal Gibanga Waterworks and 
                                    Multipurpose Cooperative is to help verify your identity and income. 
                                    When documentation is needed, typically you`ll be asked to 
                                    provide:</li>
                                    <ul style="list-style-type:circle;">
                                        <li>Proof of identity, such as government i.d or any valid 
                                            i.d</li>
                                        <li>Certificate of residency (resident only of Balubal or 
                                            Gibanga) with dry seal and signature of your 
                                            Barangay Captain.
                                            </li>
                                    </ul>
                                
                                <li> Must have must have share capital at least 200 pesos.</li>
                                <li> Must have two co makers with the share capital also. </li>    
                            </ul>
                        </div>
        
                        <div>
                            <span><b>4. How much can I borrow with a personal loan and how long can I 
                                borrow?</b></span>
                            <ul>
                                <li>The Balubal- Gibanga Waterworks and Multipurpose Cooperative 
                                    will lend money to the borrower depending to their financial 
                                    situation. However, if the borrower is a first time borrower then they 
                                    are only requiring loan the amount 3,000 thousand pesos with the 
                                    duration period four months to settle the loan.</li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>5. Can I pay my loan early?</b></span>
                            <ul>
                                <li>Yes, of course. You may pay ahead of your loan. It will be 
                                    considered as advance payment and will be posted to the 
                                    corresponding month it is intended.
                                    </li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>6. What is the effect of delayed payment?</b></span>
                            <ul>
                                <li>You will be marked as delinquent and it will affect your payment 
                                    history which is being monitored every month. One's 
                                    creditworthiness is based on the credit history which includes all 
                                    your payment behavior from the start of your application up to your 
                                    repayment stage. It will eventually be submitted to the Credit 
                                    Committee Information which handles all loan related information of 
                                    a borrower like you. Being a delinquent will affect your future 
                                    possible loans. </li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>7. How does one become delinquent?</b></span>
                            <ul>
                                <li>One can become delinquent depending on borrower behavior 
                                    during repayment. The poorer the behavior, the higher the 
                                    delinquency status will be and the higher the actions will be. 
                                    Actions may vary from contacting the guarantor, personal check-ins 
                                    to subjecting to legal actions. What is the effect of missed 
                                    payment? When you missed a payment, all unsettled amount will 
                                    be added to your outstanding balance before interest accrues not to 
                                    mention being tagged as a delinquent. For example, your monthly 
                                    due is at P2,000 with your current balance at P10,000.00, but you 
                                    won’t be able to settle this month, interest accrual will apply to 
                                    P12,000 (monthly due of P2000+ outstanding balance P10,000). 
                                    This is also the case for underpaid or partial payment. There will 
                                    also be a penalty for late payments which is indicated in your 
                                    signed ISLA. You may check it at Annex A. </li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>8. What is delinquency status?</b></span>
                            <ul>
                                <li>Delinquency is the status of a loan once a payment is not full, late 
                                    or missed. The loan remains delinquent until payments are made to 
                                    cover past due amount and bring the account current. Delinquency 
                                    has different stages and actions depending on the number of days 
                                    past due date. If I'm a good borrower. what are the perks waiting for 
                                    me? If you always pay on time or in advance and no deferment of 
                                    loans, we can give you an alumni loans (can be used in business, 
                                    housing, etc.) with lower interest rates, a lifetime referral to our 
                                    employer partners, access to profession support network -
                                    coaching for job offers, work, and others. You can also have access 
                                    to future investee programs and material.</li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>9. What if I`m good borrower, what are the perks waiting for me?
                            </b></span>
                            <ul>
                                <li>If you always pay on time or in advance and no deferment of loans, 
                                    we can give you an assurance that your next apply will be smooth 
                                    and guarantee that what amount you will be going to loan next time 
                                    will be possible.</li>
                            </ul>
                        </div>
        
                        <div>
                            <span><b>10.Can I apply for other type of loan once I have fully paid my current/ existing 
                                loan?</b></span>
                            <ul>
                                <li>No. Balubal – Gibanga Waterworks and Multipurpose Cooperative 
                                    only offer personal loan to the members.
                                    </li>
                            </ul>
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
                </div>
            </div>


            <!-- Modal -->
            <div style="color: black" class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">ABOUT</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    
                        <div>
                            <span><b>HISTORY</b></span>
                            <ul>
                                Noong 1984 ay nagkaroon ng pagbubuo ng pinagsamang bayanihan 
                                ng mga residente ng Barangay Balubal at Barangay Gibanga. Ito ay 
                                pinangunahan ng mga boluntaryong mamumuno upang makabuo ng isang 
                                Pamunuan na kikilalanin para sa gagawing mga aktibidad ng samahan.
                            </ul>
                            <ul>
                                Kinilala ang samahan sa pangalang BARANGAY BALUBAL-GIBANGA RURAL 
                                WATERWORKS ASSOCIATION INCORPORATED. Ito ay narehistro sa pamamahala 
                                ng LUA (Local Utilities Administration).
                            </ul>
                            <ul>
                                Sa pamamagitan ng mga kapasiyahan ay napagkalooban ito ng mga tulong- 
                                pinansyal buhat sa ibat-ibang sangay ng pamahalaan. Ang mga tulong-pinansyal 
                                ay maayos na naipamili ng mga materyales sa LUA (Local Utilities Administration), 
                                dito ay nabigyan sila ng mababang presyo ng mga materyales.
                            </ul>
                            <ul>
                                Patuloy na pinagsanib ang pwersa ng BAYANIHAN hanggang sa makapag-paahon sila ng 
                                sariwang tubig buhat sa isang bukal na nakatayo sa bundok ng Banahaw.Napadaloy ito 
                                sa bahayang pook ng dalawang Barangay. Nagkasya muna sila sa pakinabang na grupong -igiban.
                            </ul>
                            <ul>
                                Unti-unti ay nagkaroon ito ng mga kasapi sa pamamagitan ng pakikibayani 
                                sa mga gawain ng patubig ng samahan.
                            </ul>
                            <ul>
                                Sa patuloy na paglipas ng panahon ay dumating ang bagong henerasyon ng pamunuan 
                                at mga kasapi.Kung kaya't sa bisa ng pinagtibay na kapasiyahan ng mga kasapi na 
                                ginanap noong ika- 30 ng Marso, taong 2008, Taunang Pagpupulong ay nairehistro 
                                ito sa CDA (Cooperative Development Authority) upang maging ganap na Kooperatiba.
                            </ul>
                            <ul>
                                Noong September 8, 2008 unang ipinagkaloob ang COR(Certificate of Registration) 
                                ng samahan, sa ilalim ng R.A. 9520,
                            </ul>
                            <ul>
                                Hanggang sa kasalukuyan, sa pamamahala ng tumatayong pamunuan ay napagkakalooban 
                                ang samahan ng pagkilala o "Certificate of Good Standing" sa pagsunod sa mga 
                                alituntuning ipinatutupad ng Cooperative Development Authority.
                            </ul>

                        </div>

                        <div>
                            <span><b>VISION</b></span>
                            <ul>
                                "Mapagkalingang kooperatiba na tutugon sa pangangailangan sa tubig ay maitaas ang antas ng kabuhayan ng bawat kasapi"
                            </ul>
                        </div>

                        <div>
                            <span><b>MISSION</b></span>
                            <ul>
                                "Ipalaganap ang pagkakaisa ng samahan sa pagbibigay ng kaalaman sa pag nenegosyo" 
                            </ul>
                        </div>

                        <div>
                            <span><b>ORGANIZATIONAL CHART</b></span>
                            <br>
                            <ul>
                                <b>BOARD OF DIRECTORS</b><br>                                               
                                    Chairman : Victoriano, De Guzman <br>
                                    Vice Chairperson :  Cashmir Quevada <br>
                                    BOD Member : <br>
                                    &nbsp; : Jimmy Calatrava <br>
                                    &nbsp; : Ana Lorena <br>
                                    &nbsp; : Gloria Albindo <br>
                            </ul>

                            <ul>
                                <b>OFFICE STAFF</b><br>                                               
                                    Teasurer : Leonora Lorena <br>
                                    Manager :  Regalado Umilda <br>
                                    Bookkeeper : Roberito De Guzman <br>
                                    BOD Secretary : Jalyn Rivera <br>
                            </ul>

                            <ul>
                                <b>PLUMPER</b><br>                                               
                                    Chef. Plumber :  Beuben Espinosa <br>
                                    Plumber : <br>
                                    &nbsp; :  Zoilo JavidPlumber <br>
                                    &nbsp; :  Rocky De Chavez <br>
                                    &nbsp; :   Milo Francisco <br>
                            </ul>

                            <ul>
                                <b>CREDIT COMMITTEE</b><br>                                               
                                Chairperson : Cadano, Gina Albindo <br>
                                Vice Chairperson :  Alviso, Renante Abas <br>
                                Secretary  : Mapagdalita, Nerio Abas <br>
                            </ul>

                            <ul>
                                <b>EDUCATION AND TRANING COMMITTEE</b><br>                                               
                                Chairperson :  Cashmir Quevada <br>
                                Vice Chairperson : Quevada, Arlene Rabina <br>
                                Secretary : Pabillonia, Arlene <br>
                            </ul>

                            <ul>
                                <b>ETHICS COMMITTEE</b><br>                                               
                                Chairperson : Tuyo, Socorro Valle <br>
                                Vice Chairperson : Greganda, Marilyn Calusin <br>
                                Secretary: Calusin Adelina Caag <br>
                            </ul>

                            <ul>
                                <b>MEDIATION AND  CONCILIATION COMMITTEE</b><br>                                               
                                Chairperson: Justina Patricio <br>
                                Vice Chairperson : Rabina, Teresita Bello <br>
                                Secretary : Albos, Wilson Andres <br>
                            </ul>

                            <ul>
                                <b>ELECTION COMMITTEE</b><br>                                               
                                Chairperson : Maaliw, Luisa Albindo <br>
                            </ul>
                        </div>

                        

                        

                        

                        

                        

                        


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
                </div>
            </div>

        </main>
    </div>
</body>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
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



</html>


