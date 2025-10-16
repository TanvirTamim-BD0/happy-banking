<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\CreditCardController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\DocumentationCategoryController;
use App\Http\Controllers\API\DocumentationController;


Route::group(['namespace' => 'API'], function () {
    Route::post('/test-message', [AuthController::class, 'testMessage'])->name('test-message');

    //Auth Controller register login
    Route::get('/get-profession-data', [AuthController::class, 'getProfessionData'])->name('get-profession-data');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    //To verify otp...
    Route::post('otp-verify-code', [AuthController::class, 'verifyOtp'])->name('otp-verify-code');
    Route::post('resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');

    //To get blog api route...
    Route::get('/get-all-blog-category', [BlogController::class, 'getAllBlogCategory'])->name('get-all-blog-category');
    Route::get('/get-all-blog', [BlogController::class, 'getAllBlogData'])->name('get-all-blog');
    Route::get('/get-blog-details/{id}', [BlogController::class, 'getBlogDetailsData'])->name('get-blog-details');
    Route::get('/get-category-wise-blog/{id}', [BlogController::class, 'getCategoryWiseBlog'])->name('get-category-wise-blog');
    
    Route::group(['middleware' => 'auth:api'], function () {
        
        Route::get('/dashboard', [App\Http\Controllers\API\HomeController::class, 'index'])->name('home');

        //For documentation category & documentation and frontend note type & frontend note...
        Route::apiResource('documentation-category', DocumentationCategoryController::class);
        Route::apiResource('documentation', DocumentationController::class);
        Route::get('/get-documentation-category', [App\Http\Controllers\API\DocumentationController::class, 'getDocumentationCategory'])->name('profile');
        Route::get('/get-documentation-with-category/{id}', [App\Http\Controllers\API\DocumentationController::class, 'getDocumentationWisDocsCate'])->name('profile');
        Route::get('/get-frontend-note-type', [App\Http\Controllers\API\DocumentationController::class, 'getFrontendNoteType'])->name('get-frontend-note-type');
        Route::get('/get-frontend-note-data', [App\Http\Controllers\API\DocumentationController::class, 'getFrontendNoteData'])->name('get-frontend-note-data');

        //To profile update...
        Route::get('/profile', [App\Http\Controllers\API\ProfileController::class, 'getUserData'])->name('profile');
        Route::post('/profile/update', [App\Http\Controllers\API\ProfileController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/profile/mobile/update', [App\Http\Controllers\API\ProfileController::class, 'profileMobileUpdate'])->name('profile.mobile.update');
        Route::post('/profile/mobile/verify', [App\Http\Controllers\API\ProfileController::class, 'profileMobileVerifyOtp'])->name('profile.mobile.verify');
        Route::post('/profile/password/update', [App\Http\Controllers\API\ProfileController::class, 'securityUpdate'])->name('profile.password.update');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        //For account services section...
        Route::get('/mfs-account-services', [App\Http\Controllers\API\ProfileController::class, 'mobileWalletAccountEditList'])->name('mfs-account-services');
        Route::get('/banking-account-services', [App\Http\Controllers\API\ProfileController::class, 'bankingWalletAccountEditList'])->name('banking-account-services');
        Route::get('/credit-card-account-services', [App\Http\Controllers\API\ProfileController::class, 'creditCardWalletAccountEditList'])->name('credit-card-account-services');

        //For account controller section...
        Route::get('/get-account-type', [AccountController::class, 'getAccountType'])->name('get-account-type');
        Route::post('/get-bank-data-with-account-type', [AccountController::class, 'getBankDataWithAccountType'])->name('get-bank-data-with-account-type');
        Route::get('/get-account-data', [AccountController::class, 'getAccountData'])->name('get-account-data');
        Route::get('/get-bank-account-data', [AccountController::class, 'getBankAccountData'])->name('get-bank-account-data');
        Route::get('/get-mobile-wallet-account-data', [AccountController::class, 'getMobileWalletAccountData'])->name('get-mobile-wallet-account-data');
        Route::post('/save-account-data', [AccountController::class, 'saveAccountData'])->name('save-account-data');
        Route::get('/edit-account-data/{id}', [AccountController::class, 'editAccountData'])->name('edit-account-data');
        Route::put('/update-account-data/{id}', [AccountController::class, 'updateAccountData'])->name('update-account-data');
        Route::delete('/delete-account-data/{id}', [AccountController::class, 'deleteAccountData'])->name('delete-account-data');
        Route::get('/active-account-data/{id}', [AccountController::class, 'activeAccountData'])->name('active-account-data');
        Route::get('/inactive-account-data/{id}', [AccountController::class, 'inactiveAccountData'])->name('inactive-account-data');

        //For credit card controller section...
        Route::get('/get-credit-card-type', [CreditCardController::class, 'getCreditCardType'])->name('get-credit-card-type');
        Route::get('/get-credit-card-data', [CreditCardController::class, 'getCreditCardData'])->name('get-credit-card-data');
        Route::post('/save-credit-card-data', [CreditCardController::class, 'saveCreditCardData'])->name('save-credit-card-data');
        Route::get('/edit-credit-card-data/{id}', [CreditCardController::class, 'editCreditCardData'])->name('edit-credit-card-data');
        Route::put('/update-credit-card-data/{id}', [CreditCardController::class, 'updateCreditCardData'])->name('update-credit-card-data');
        Route::delete('/delete-credit-card-data/{id}', [CreditCardController::class, 'deleteCreditCardData'])->name('delete-credit-card-data');
        Route::post('/card-currency-enabled/{id}', [CreditCardController::class, 'enableCurrency'])->name('card-currency-enabled');
        Route::post('/card-currency-disabled/{id}', [CreditCardController::class, 'disableCurrency'])->name('card-currency-disabled');
        Route::get('/active-credit-card-data/{id}', [CreditCardController::class, 'activeCreditCardData'])->name('active-credit-card-data');
        Route::get('/inactive-credit-card-data/{id}', [CreditCardController::class, 'inactiveCreditCardData'])->name('inactive-credit-card-data');
        
        //For credit card reminder, beneficiary, accountPayment controller section...
        Route::apiResource('credit-card-reminder', CreditCardReminderController::class);
        Route::get('/bill-reminder-date-with-credit-card-id/{id}', [App\Http\Controllers\API\CreditCardReminderController::class, 'getBillReminderDataWithCardId'])->name('bill-reminder-date-with-credit-card-id');
        Route::get('/get-active-reminder-session', [App\Http\Controllers\API\CreditCardReminderController::class, 'getActiveReminderSession'])->name('get-active-reminder-session');
        Route::get('/get-unpaid-card-reminder-data', [App\Http\Controllers\API\CreditCardReminderController::class, 'getUnPaidCreditCardReminder'])->name('get-unpaid-card-reminder-data');
        Route::post('credit-card-bill-reminder-status-change', [App\Http\Controllers\API\CreditCardReminderController::class, 'changeStatusCreditCardBillReminder'])->name('credit-card-bill-reminder-status-change');
        
        Route::apiResource('beneficiary', BeneficiaryController::class);
        Route::get('/get-bank-beneficiary-data', [App\Http\Controllers\API\BeneficiaryController::class, 'getBankBeneficiaryData'])->name('get-bank-beneficiary-data');
        Route::get('/get-mfs-beneficiary-data', [App\Http\Controllers\API\BeneficiaryController::class, 'getMfsBaneficiaryData'])->name('get-mfs-beneficiary-data');

        // Route::get('/get-beneficiary-type', [App\Http\Controllers\API\BeneficiaryController::class, 'getBeneficiaryType'])->name('get-beneficiary-type');



        //For account to account payment controller section...
        Route::apiResource('account-payment', AccountPaymentController::class);
        Route::get('/get-transaction-category-data', [App\Http\Controllers\API\AccountPaymentController::class, 'getTransactionCategory'])->name('get-transaction-category-data');
        Route::get('/get-account-details-with-selected-account-for-ata/{id}', [App\Http\Controllers\API\AccountPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-ata');
        Route::post('/get-bank-list-data-for-ata/{id}', [App\Http\Controllers\API\AccountPaymentController::class, 'getBankListData'])->name('get-bank-list-data-for-ata');
        Route::post('/get-account-data-with-bank-for-ata/{id}', [App\Http\Controllers\API\AccountPaymentController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-ata');
        Route::post('/account-to-account/{id}', [App\Http\Controllers\API\AccountPaymentController::class, 'accountToAccountPayment'])->name('account-to-account');

        //For account to mfs payment controller section...
        Route::get('/get-account-to-mfs-payment', [App\Http\Controllers\API\AccountToMFSPaymentController::class, 'getAccountToMFSPaymentData'])->name('get-account-to-mfs-payment');
        Route::get('/get-account-details-with-selected-account-for-atm/{id}', [App\Http\Controllers\API\AccountToMFSPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-atm');
        Route::post('/get-mobile-wallet-list-data-for-atm/{id}', [App\Http\Controllers\API\AccountToMFSPaymentController::class, 'getMobileWalletListData'])->name('get-mobile-wallet-list-data-for-atm');
        Route::post('/get-account-data-with-mobile-wallet-for-atm/{id}', [App\Http\Controllers\API\AccountToMFSPaymentController::class, 'getAccountDataWithMobileWallet'])->name('get-account-data-with-mobile-wallet-for-atm');
        Route::post('/account-to-mfs/{id}', [App\Http\Controllers\API\AccountToMFSPaymentController::class, 'accountToMFSPayment'])->name('account-to-mfs');
        
        //For mfs to account payment controller section...
        Route::get('/get-mfs-to-account-payment', [App\Http\Controllers\API\MFSToAccountPaymentController::class, 'getMFSToAccountPaymentData'])->name('get-mfs-to-account-payment');
        Route::get('/get-account-details-with-selected-account-for-mta/{id}', [App\Http\Controllers\API\MFSToAccountPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-mta');
        Route::post('/get-bank-list-data-for-mta/{id}', [App\Http\Controllers\API\MFSToAccountPaymentController::class, 'getBankListData'])->name('get-bank-list-data-for-mta');
        Route::post('/get-account-data-with-bank-for-mta/{id}', [App\Http\Controllers\API\MFSToAccountPaymentController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-mta');
        Route::post('/mfs-to-account/{id}', [App\Http\Controllers\API\MFSToAccountPaymentController::class, 'mfsToAccountPayment'])->name('mfs-to-account');
        
        //For mfs to mfs payment controller section...
        Route::get('/get-mfs-to-mfs-payment', [App\Http\Controllers\API\MFSToMFSPaymentController::class, 'getMFSToMFSPaymentData'])->name('get-mfs-to-mfs-payment');
        Route::get('/get-account-details-with-selected-account-for-mtm/{id}', [App\Http\Controllers\API\MFSToMFSPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-mtm');
        Route::post('/get-mobile-wallet-list-data-for-mtm/{id}', [App\Http\Controllers\API\MFSToMFSPaymentController::class, 'getMobileWalletListData'])->name('get-mobile-wallet-list-data-for-mtm');
        Route::post('/get-account-data-with-mobile-wallet-for-mtm/{id}', [App\Http\Controllers\API\MFSToMFSPaymentController::class, 'getAccountDataWithMobileWallet'])->name('get-account-data-with-mobile-wallet-for-mtm');
        Route::post('/mfs-to-mfs/{id}', [App\Http\Controllers\API\MFSToMFSPaymentController::class, 'mfsToMFSPayment'])->name('mfs-to-mfs');
        
        //For Account To Wallet payment controller section...
        Route::get('/get-account-to-pocket-payment', [App\Http\Controllers\API\AccountToPocketPaymentController::class, 'getAccountToPocketPaymentData'])->name('get-account-to-pocket-payment');
        Route::get('/get-account-details-with-selected-account-for-atp/{id}', [App\Http\Controllers\API\AccountToPocketPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-atp');
        Route::post('/account-to-pocket/{id}', [App\Http\Controllers\API\AccountToPocketPaymentController::class, 'accountToPocketPayment'])->name('account-to-pocket');
        
        //For Wallet To Account payment controller section...
        Route::get('/get-pocket-to-account-payment', [App\Http\Controllers\API\PocketToAccountPaymentController::class, 'getPocketToAccountPaymentData'])->name('get-pocket-to-account-payment');
        Route::get('/get-account-details-with-selected-account-for-pta', [App\Http\Controllers\API\PocketToAccountPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-pta');
        Route::post('/get-account-data-with-bank-for-pta', [App\Http\Controllers\API\PocketToAccountPaymentController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-pta');
        Route::post('/pocket-to-account', [App\Http\Controllers\API\PocketToAccountPaymentController::class, 'pocketToAccountPayment'])->name('pocket-to-account');

        //For MFS To Wallet payment controller section...
        Route::get('/get-mfs-to-pocket-payment', [App\Http\Controllers\API\MFSToPocketPaymentController::class, 'getMFSToPocketPaymentData'])->name('get-mfs-to-pocket-payment');
        Route::get('/get-account-details-with-selected-account-for-mtp/{id}', [App\Http\Controllers\API\MFSToPocketPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-mtp');
        Route::post('/mfs-to-pocket/{id}', [App\Http\Controllers\API\MFSToPocketPaymentController::class, 'mfsToPocketPayment'])->name('mfs-to-pocket');

        //For Wallet To MFS payment controller section...
        Route::get('/get-pocket-to-mfs-payment', [App\Http\Controllers\API\PocketToMFSPaymentController::class, 'getPocketToMFSPaymentData'])->name('get-pocket-to-mfs-payment');
        Route::get('/get-account-details-with-selected-account-for-ptm', [App\Http\Controllers\API\PocketToMFSPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-ptm');
        Route::post('/get-account-data-with-mobile-wallet-for-ptm', [App\Http\Controllers\API\PocketToMFSPaymentController::class, 'getAccountDataWithMobileWallet'])->name('get-account-data-with-mobile-wallet-for-ptm');
        Route::post('/pocket-to-mfs', [App\Http\Controllers\API\PocketToMFSPaymentController::class, 'pocketToMFSPayment'])->name('pocket-to-mfs');

        //For Wallet To Card payment controller section...
        Route::get('/get-pocket-to-credit-card-payment', [App\Http\Controllers\API\PocketToCreditCardPaymentController::class, 'getPocketToCreditCardPaymentData'])->name('get-pocket-to-credit-card-payment');
        Route::get('/get-account-details-with-selected-account-for-ptcc', [App\Http\Controllers\API\PocketToCreditCardPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-ptcc');
        Route::post('/get-account-data-with-bank-for-ptcc', [App\Http\Controllers\API\PocketToCreditCardPaymentController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-ptcc');
        Route::post('/pocket-to-credit-card', [App\Http\Controllers\API\PocketToCreditCardPaymentController::class, 'pocketToCreditCardPayment'])->name('pocket-to-credit-card');

        //For card to account payment controller section...
        Route::get('/get-card-to-account-payment', [App\Http\Controllers\API\CardToAccountPaymentController::class, 'getCardToAccountPaymentData'])->name('get-card-to-account-payment');
        Route::get('/get-account-details-with-selected-account-for-cta/{id}', [App\Http\Controllers\API\CardToAccountPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-cta');
        Route::post('/get-bank-list-data-for-cta/{id}', [App\Http\Controllers\API\CardToAccountPaymentController::class, 'getBankListData'])->name('get-bank-list-data-for-cta');
        Route::post('/get-account-data-with-bank-for-cta/{id}', [App\Http\Controllers\API\CardToAccountPaymentController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-cta');
        Route::post('/card-to-account/{id}', [App\Http\Controllers\API\CardToAccountPaymentController::class, 'cardToAccountPayment'])->name('card-to-account');

        //For card to mfs payment controller section...
        Route::get('/get-card-to-mfs-payment', [App\Http\Controllers\API\CardToMFSPaymentController::class, 'getCardToMFSPaymentData'])->name('get-card-to-mfs-payment');
        Route::get('/get-account-details-with-selected-account-for-ctm/{id}', [App\Http\Controllers\API\CardToMFSPaymentController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-ctm');
        Route::post('/get-mobile-wallet-list-data-for-ctm/{id}', [App\Http\Controllers\API\CardToMFSPaymentController::class, 'getMobileWalletListData'])->name('get-mobile-wallet-list-data-for-ctm');
        Route::post('/get-account-data-with-mobile-wallet-for-ctm/{id}', [App\Http\Controllers\API\CardToMFSPaymentController::class, 'getAccountDataWithMobileWallet'])->name('get-account-data-with-mobile-wallet-for-ctm');
        Route::post('/card-to-mfs/{id}', [App\Http\Controllers\API\CardToMFSPaymentController::class, 'cardToMFSPayment'])->name('card-to-mfs');

        //For Bank, MFS, Wallet To Card Account Transfer...
        Route::get('/get-account-details-with-selected-account-for-ptc/{id}', [App\Http\Controllers\API\CreditCardController::class, 'getAccountDetailsWSA'])->name('get-account-details-with-selected-account-for-ptc');
        Route::post('/pocket-to-card/{id}', [App\Http\Controllers\API\CreditCardController::class, 'pocketToCardTransfer'])->name('pocket-to-card');
        //For Bank
        Route::get('/get-account-details-with-selected-account-for-atc/{id}', [App\Http\Controllers\API\CreditCardController::class, 'getAccountDetailsWSAFC'])->name('get-account-details-with-selected-account-for-atc');
        Route::post('/get-account-data-with-bank-for-atc/{id}', [App\Http\Controllers\API\CreditCardController::class, 'getAccountDataWithBank'])->name('get-account-data-with-bank-for-atc');
        Route::post('/account-to-card/{id}', [App\Http\Controllers\API\CreditCardController::class, 'accountToCardTransfer'])->name('account-to-card');
        //For MFS
        Route::get('/get-account-details-with-selected-account-for-mtc/{id}', [App\Http\Controllers\API\CreditCardController::class, 'getAccountDetailsWMFS'])->name('get-account-details-with-selected-account-for-mtc');
        Route::post('/get-account-data-with-mfs-for-mtc/{id}', [App\Http\Controllers\API\CreditCardController::class, 'getAccountDataWithMFS'])->name('get-account-data-with-mfs-for-mtc');
        Route::post('/mfs-to-card/{id}', [App\Http\Controllers\API\CreditCardController::class, 'mfsToCardTransfer'])->name('mfs-to-card');
    
        //For income controller section...
        Route::get('/get-income-type', [App\Http\Controllers\API\IncomeController::class, 'getIncomeTypeData'])->name('get-income-type');
        Route::post('/get-bank-mobile-wallet-data-with-type', [App\Http\Controllers\API\IncomeController::class, 'getBankMFSDataWithIncomeType'])->name('get-bank-mobile-wallet-data-with-type');
        Route::post('/get-account-data-with-bank-or-mfs', [App\Http\Controllers\API\IncomeController::class, 'getAccountDataWithBankOrMFS'])->name('get-account-data-with-bank-or-mfs');
        Route::get('/get-income-source', [App\Http\Controllers\API\IncomeController::class, 'getIncomeSourceData'])->name('get-income-source');
        Route::post('/add-income-data', [App\Http\Controllers\API\IncomeController::class, 'addIncomeData'])->name('add-income-data');
        Route::get('/get-income-data', [App\Http\Controllers\API\IncomeController::class, 'getIncomeData'])->name('get-income-data');

        //For expense controller section...
        Route::get('/get-expense-type', [App\Http\Controllers\API\ExpenseController::class, 'getExpenseTypeData'])->name('get-expense-type');
        Route::post('/get-bank-mobile-wallet-data-with-type-for-expense', [App\Http\Controllers\API\ExpenseController::class, 'getBankMFSDataWithExpenseType'])->name('get-bank-mobile-wallet-data-with-type-for-expense');
        Route::post('/get-account-data-with-bank-or-mfs-for-expense', [App\Http\Controllers\API\ExpenseController::class, 'getAccountDataWithBankOrMFSForExpense'])->name('get-account-data-with-bank-or-mfs-for-expense');
        Route::get('/get-expense-source', [App\Http\Controllers\API\ExpenseController::class, 'getExpenseSourceData'])->name('get-expense-source');
        Route::post('/add-expense-or-transaction-data', [App\Http\Controllers\API\ExpenseController::class, 'addExpenseOrTransactionData'])->name('add-expense-or-transaction-data');
        Route::post('/get-single-credit-card-data', [App\Http\Controllers\API\ExpenseController::class, 'getSingleCreditCardData'])->name('get-single-credit-card-data');
        Route::get('/get-expense-or-transaction-data', [App\Http\Controllers\API\ExpenseController::class, 'getExpenseOrTransactionData'])->name('get-expense-or-transaction-data');
        
        //No needs...
        Route::get('/get-credit-data-with-account-id/{id}', [App\Http\Controllers\API\ExpenseController::class, 'getCreditDataWithAccountId'])->name('get-credit-data-with-account-id');
        Route::get('/get-debit-data-with-account-id/{id}', [App\Http\Controllers\API\ExpenseController::class, 'getDebitDataWithAccountId'])->name('get-debit-data-with-account-id');
        Route::get('/get-credit-debit-data-with-account-id/{id}', [App\Http\Controllers\API\ExpenseController::class, 'getCreditDebitDataWithAccountId'])->name('get-credit-debit-data-with-account-id');
        Route::get('/get-debit-data-with-credit-card-account-id/{id}', [App\Http\Controllers\API\ExpenseController::class, 'getDebitDataWithCreditCardAccountId'])->name('get-debit-data-with-credit-card-account-id');


        //For Pocket, Bank & MFS  Transaction...
        Route::get('get-pocket-wallet-account-transaction-all-list', [App\Http\Controllers\API\TransactionController::class, 'transactionPocketWalletAccountAllList'])->name('get-pocket-wallet-account-transaction-all-list');
        Route::get('get-pocket-wallet-account-transaction-credit-list', [App\Http\Controllers\API\TransactionController::class, 'transactionPocketWalletAccountCreditList'])->name('get-pocket-wallet-account-transaction-credit-list');
        Route::get('get-pocket-wallet-account-transaction-debit-list', [App\Http\Controllers\API\TransactionController::class, 'transactionPocketWalletAccountDebitList'])->name('get-pocket-wallet-account-transaction-debit-list');
        //Mobile...
        Route::get('get-mobile-wallet-account-transaction-all-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionMobileWalletAccountAllList'])->name('get-mobile-wallet-account-transaction-all-list');
        Route::get('get-mobile-wallet-account-transaction-credit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionMobileWalletAccountCreditList'])->name('get-mobile-wallet-account-transaction-credit-list');
        Route::get('get-mobile-wallet-account-transaction-debit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionMobileWalletAccountDebitList'])->name('get-mobile-wallet-account-transaction-debit-list');
        //Bank...
        Route::get('get-banking-wallet-account-transaction-all-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionBankingWalletAccountAllList'])->name('get-banking-wallet-account-transaction-all-list');
        Route::get('get-banking-wallet-account-transaction-credit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionBankingWalletAccountCreditList'])->name('get-banking-wallet-account-transaction-credit-list');
        Route::get('get-banking-wallet-account-transaction-debit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionBankingWalletAccountDebitList'])->name('get-banking-wallet-account-transaction-debit-list');
        //Card...
        Route::get('get-card-wallet-account-transaction-all-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionCardWalletAccountAllList'])->name('get-card-wallet-account-transaction-all-list');
        Route::get('get-card-wallet-account-transaction-credit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionCardWalletAccountCreditList'])->name('get-card-wallet-account-transaction-credit-list');
        Route::get('get-card-wallet-account-transaction-debit-list/{id}', [App\Http\Controllers\API\TransactionController::class, 'transactionCardWalletAccountDebitList'])->name('get-card-wallet-account-transaction-debit-list');

        //Invoice .............
        Route::post('transaction-invoice', [App\Http\Controllers\API\TransactionController::class, 'tranjectionInvoice'])->name('tranjection-invoice');
        Route::post('transactions-invoice', [App\Http\Controllers\API\TransactionController::class, 'tranjectionInvoicePocketWallet'])->name('tranjections-invoice');
        Route::post('wallet-transaction-invoice', [App\Http\Controllers\API\TransactionController::class, 'walletTranjectionInvoice'])->name('wallet-tranjection-invoice');
        Route::post('card-transaction-invoice', [App\Http\Controllers\API\TransactionController::class, 'cardTranjectionInvoice'])->name('card-tranjection-invoice');
        Route::post('card-bill-payment-invoice', [App\Http\Controllers\API\TransactionController::class, 'cardBillPaymentInvoice'])->name('card-bill-payment-invoice');
        Route::post('card-wallet-bill-payment-invoice', [App\Http\Controllers\API\TransactionController::class, 'cardWalletBillPaymentInvoice'])->name('card-wallet-bill-payment-invoice');
        Route::post('income-invoice', [App\Http\Controllers\API\TransactionController::class, 'incomeInvoice'])->name('income-invoice');
        Route::post('income-wallet-invoice', [App\Http\Controllers\API\TransactionController::class, 'incomeWalletInvoice'])->name('income-wallet-invoice');
        Route::post('expense-invoice', [App\Http\Controllers\API\TransactionController::class, 'expenseInvoice'])->name('expense-invoice');
        Route::post('expense-card-invoice', [App\Http\Controllers\API\TransactionController::class, 'expenseCardInvoice'])->name('expense-card-invoice');
        Route::post('expense-wallet-invoice', [App\Http\Controllers\API\TransactionController::class, 'expenseWalletInvoice'])->name('expense-wallet-invoice');


        //tranfer and income expesne revert..............
        Route::post('transfer-data-filter', [App\Http\Controllers\API\HomeController::class, 'transferDataFilter'])->name('transfer-data-filter');
        Route::post('transfer-revert', [App\Http\Controllers\API\HomeController::class, 'transferRevert'])->name('transfer-revert');
        Route::post('income-expense-data-filter', [App\Http\Controllers\API\HomeController::class, 'incomeExpenseDataFilter'])->name('income-expense-data-filter');
        Route::post('income-expense-revert', [App\Http\Controllers\API\HomeController::class, 'incomeExpenseRevert'])->name('income-expense-revert');

        //To get push notificaation api...
        Route::post('/store-token', [App\Http\Controllers\API\PushNotificationController::class, 'updateDeviceToken'])->name('store.token');
        Route::post('/send-push-notification', [App\Http\Controllers\API\PushNotificationController::class, 'pushNotificationSend'])->name('send.push-notification');
        Route::get('/get-push-notification', [App\Http\Controllers\API\PushNotificationController::class, 'getPushNotificationData'])->name('get-push-notification');

    });

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
