<html>
<head>
    <title>Receipt</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}">  --}}
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<style>
  table, td, th {  
    border: 1px solid;
    text-align: left;
  }
  
  table {
    border-collapse: collapse;
    width: 100%;
  }
  
  th, td {
    padding: 2px;
    width: 25%
  }
  </style>
</head>
<body>
    
    <div style="text-align:center" ><span style="font-size: 15px; text-align:center"><b>BALUBAL-GIBANGA WATERWORKS and MULTI-PURPOSE COOPERATIVE</b></span></div>
    <br>
    <div style="text-align:center" ><span style="font-size: 10px; "><b>Email Add. Balubal_GibangaWaterworksandMPC@yahoo.com</b></span></div>
    <br>
    <div style="text-align:center"><span style="font-size: 10px"><b>Brgy. Balubal, Sariaya, Quezon</b></span></div>
    <br>
    <div style="text-align:center"><span style="font-size: 10px"><b>Non-VAT Reg TIN: 269-658-257-000</b></span></div>
    
    {{-- <br>
    <div class="container d-flex justify-content-end"><span><b>Date Released:</b>&nbsp;&nbsp;<u>{{ $current_time }}</u></span></div> --}}

    <div class="container d-flex justify-content-between">
        <div class="row">
          <div class="col-sm">
            {{-- <br>
            <div><span><b>Passbook Number:</b>&nbsp;&nbsp;<u>{{ $select->account_number }}</u></span></div>
            <br>
            <div><span><b>NAME:</b>&nbsp;&nbsp;<u>{{ $select->client_firstname }}&nbsp;{{ $select->client_lastname }}</u></span></div>
            <br>
            <div><span><b>Loan Amount:</b>&nbsp;&nbsp;<u>{{ $loanfo->amount_approved }}</u></span></div>
            <br>
            <div><span><b>Term of Payment:</b>&nbsp;&nbsp;<u>{{ $loanfo->payment_term }}</u></span></div>
            <br>
            <div><span><b>Date Released:</b>&nbsp;&nbsp;<u>{{ $statusDate->status_date }}</u></span></div>
            <br>
            <div><span><b>Net Proceeds of Loan:</b>&nbsp;&nbsp;<u>{{ $net_proceeds }}</u></span></div>
            <br> --}}

            <br>
            <div style="justify-content:space-between; border-bottom:solid;">
                <span style=""><b>OFFICIAL RECEIPT</b></span>
                <span style="float: right;"><b style="margin-right: 150px;">Date: {{ $current_time }}</b></span>
            </div>
            <div style="justify-content:space-between; border-bottom:solid;">
              <span style=""><b>RECEIVED FROM</b></span>
            </div>
            <div style="justify-content:space-between; border-bottom:solid;">
              <span style=""><b>TIN </b></span>
              <span style="float: right; border-left:solid;"><b style="margin-right: 190px; padding:30px">Terms</b></span>
            </div>
            <div style="justify-content:space-between; border-bottom:solid;">
              <span style=""><b>Address</b></span>
              <span style="float: right; border-left:solid;"><b style="margin-right: 99px; padding:30px">OSCA/PWD ID No.</b></span>
            </div>
            <div style="justify-content:space-between; border-bottom:solid;">
              <span style=""><b>Business Style</b></span>
              <span style="float: right; border-left:solid;"><b style="margin-right: 168px; padding:30px">Signature</b></span>
            </div>
            <br>

            <table>
              <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <td>Membership Fee</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>PMES Fee</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Share Capital</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Deposit</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Interest On Loan</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Others:</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="width: 50%"><b>TOTAL AMOUNT DUE<b></td>
                <td><b>P</b></td>
                <td></td>
              </tr>
            </table>


            </div>
          </div>
          <br>
          <div style="text-align:right"><span style="font-size: 13px">By:<b>_________________________________</b></span></div>
          <div style="text-align:right"><span style="font-size: 10px; margin-right: 50px;" >Cashier/ Authorized Signature</span></div>
          <br>
          <br>
          <div style="text-align:right;  margin-right: 50px;"><span style="font-size: 13px; color:red;">NO.<b>_____________</b></span></div>
          
          <br>
          <br>
          <div style="text-align:center"><span style="font-size: 13px; font-style:italic" >“THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES“</span></div>
          <div style="text-align:center"><span style="font-size: 13px; font-style:italic" >“THIS OFFICIAL RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF ATP“</span></div>
          
          
          <div class="row">
            <div class="col-sm align-self-end">
                <div>
                  {{-- <br>
                    <div><u><span><b>DEDUCTIONS</b></u></span></div>
                    <br>
                    <div><span><b>Share Capital (5%) =</b> &nbsp;&nbsp;<u>{{ $share_capital }}</u></span></div>
                    <br>
                    <div><span><b>Savings Deposit (1.6%) =</b> &nbsp;&nbsp;<u>{{ $savings_deposit }}</u></span></div>
                    <br>
                    <div><span><b>Interest On Loan (12%) =</b> &nbsp;&nbsp;<u>{{ $interest_on_loan }}</u></span></div>
                    <br>
                    <div><span><b>Service Fee (2.5%) =</b> &nbsp;&nbsp;<u>{{ $service_fee }}</u></span></div>
                    <br>
                    <div><span><b>Miscellaneous Fee (0.5%) =</b> &nbsp;&nbsp;<u>{{ $miscellaneous_fee }}</u></span></div>
                    <br> --}}
                    {{-- <div><span>Share Capital = &nbsp;&nbsp;<u>{{ $share_capital }}</u></span></div>
                    <br>
                    <div><span>Savings Deposit = &nbsp;&nbsp;<u>{{ $savings_deposit }}</u></span></div> --}}
                </div>
              </div>
          </div>
          
          {{-- <div class="row">
            <div class="col-sm">
              <br>
                <div><span><b>ADDRESS:&nbsp;&nbsp;<u>{{ $select->client_adress }}</u></b></span></div>
                <br>
                <div><span><b>Contact No:&nbsp;&nbsp;<u>{{ $select->contact_number }}</u></b></span></div>
                <br>
                <div><span><b>Deduction: OR#&nbsp;&nbsp;<u>________</u></b></span></div>
                <br>
                <div><span>Share Capital (5%) = &nbsp;&nbsp;<u>{{ $share_capital }}</u></span></div>
                <br>
                <div><span>Savings Deposit (1.6%) = &nbsp;&nbsp;<u>{{ $savings_deposit }}</u></span></div>
                <br>
                <div><span>Interest On Loan (12%) = &nbsp;&nbsp;<u>{{ $interest_on_loan }}</u></span></div>
                <br>
                <div><span>Service Fee (2.5%) = &nbsp;&nbsp;<u>{{ $service_fee }}</u></span></div>
                <br>
                <div><span>Miscellaneous Fee (0.5%) = &nbsp;&nbsp;<u>{{ $miscellaneous_fee }}</u></span></div>
                <br>
                <div><span>Insurance Fee = &nbsp;&nbsp;<u>__________________</u></span></div>
              </div>
          </div> --}}

          {{-- <div class="row">
            <div class="col-sm align-self-end">
                <div>
                  <br>
                    <div><span><b>Co-maker: 1.)</b> &nbsp;&nbsp;<u>____________________________</u></span></div>
                    <br>
                    <div><span>Printed Name: 2.) &nbsp;&nbsp;<u>____________________________</u></span></div>
                    <br>
                    <div><span><b>Purpose:</b> &nbsp;&nbsp;<u>________________________________________________________</u></span></div>
                    <br>
                </div>
              </div>
          </div>

          <div class="row">
            <div class="col-sm align-self-end">
                <div>
                  <br>
                    <div><span><b>For loan release:</b></span></div>
                    <br>
                    <div><span>Prepared by:&nbsp;&nbsp;<u>____________________________</u></span></div>
                    <br>
                    <div><span><b>Approved by:</b> &nbsp;&nbsp;<u>________________________________________________________</u></span></div>
                    <br>
                    <div><span>Received Cash Dated:&nbsp;&nbsp;<u>____________________________</u></span></div>
                    <br>
                    <div><span>Received by: &nbsp;&nbsp;<u>________________________________________________________</u></span></div>
                    <br>
                    <div><span>Released by: &nbsp;&nbsp;<u>________________________________________________________</u></span></div>
                    <br>
                    <div><span><b>Manager:</b> &nbsp;&nbsp;<u>Regalado G. Umilda</u></span></div>
                    <br>
                    <div><span><b>Treasurer:</b> &nbsp;&nbsp;<u>Leonora Lorena</u></span></div>
                    <br>
                </div>
              </div>
          </div> --}}
          
    </div>
      

</body>
 
</html>