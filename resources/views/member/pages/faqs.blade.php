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
                    <span><strong style="color: #073b3a">FAQS</strong></span>
                </div>
                <div class="card-header-btn">
                    
                </div>
            </div>
            <div class="card-body">
    
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

                <div class="function__button d-flex justify-content-end mt-2">
                    <a href="{{ route('member.account_setting') }}" class="btn btn-secondary"><i class='bx bx-arrow-back'></i>Return</a>
                </div>
                
              
              


    
                
            </div>
            
        </div>
    </div>

@endsection