<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebUser\WebUserController;
use App\Http\Controllers\WebUser\AccountListController;
use App\Http\Controllers\WebUser\PocketAccountController;
use App\Http\Controllers\WebUser\BankingAccountController;
use App\Http\Controllers\WebUser\IncomeController;
use App\Http\Controllers\WebUser\ProfileController;
use App\Http\Controllers\WebUser\MfsAccountController;
use App\Http\Controllers\WebUser\CreditCardController;
use App\Http\Controllers\WebUser\ExpenseController;
use App\Http\Controllers\WebUser\TransactionController;
use App\Http\Controllers\WebUser\BeneficiaryController;

//To get all the auth routes list of web user...
Route::middleware(['guest:webuser'])->prefix('webuser')->group(function () {
  Route::get('register',    [WebUserController::class, 'webUserRegisterPage'])->name('get-register');
  Route::post('register',   [WebUserController::class, 'webUserRegister'])->name('post-register');
  Route::get('verify-OTP',  [WebUserController::class, 'webUserLoginPage'])->name('get-verify-OTP');
  Route::get('verify-OTP/{user_mobile}',  [WebUserController::class, 'getVerifyOtpPage'])->name('get-verify-OTP-page');
  Route::post('verify-OTP', [WebUserController::class, 'webUserVerifyOtp'])->name('post-verify-OTP');
  Route::get('/password-change', [WebUserController::class, 'webUserPasswordChange'])->name('get-password-change');
  Route::get('forgot-pass-verify-OTP/{user_mobile}', [WebUserController::class, 'getForgotVerifyOtpPage'])->name('get-forgot-pass-verify-OTP-page');
  Route::post('forgot-pass-verify-OTP', [WebUserController::class, 'forgotPassVerifyOtp'])->name('forgot-pass-verify-OTP');
  Route::post('/password-update', [WebUserController::class, 'webUserPasswordUpdate'])->name('get-password-update');
  Route::get('login',       [WebUserController::class, 'webUserLoginPage'])->name('get-login');
  Route::post('login',   [WebUserController::class, 'webUserogin'])->name('post-login');
  Route::get('/password-forgot', [WebUserController::class, 'forgotPassword'])->name('password-forgot');
  Route::post('/password-forgot-otp-sent', [WebUserController::class, 'forgotPasswordOTPSent'])->name('password-forgot-otp-sent');
  Route::get('/resend-Otp-for-pass-change/{mobile}',   [WebUserController::class, 'resendOtpForPassChange'])->name('resend-Otp-for-password-change');
  Route::get('/resend-Otp/{mobile}',   [WebUserController::class, 'resendOtp'])->name('resend-Otp');
  Route::get('/blog', [WebUserController::class, 'blog'])->name('blog');
  Route::get('/blog-details/{id}', [WebUserController::class, 'blogDetails'])->name('blog-details');
  Route::get('/blog-category-wise/{id}', [WebUserController::class, 'blogCategoryWise'])->name('blog-category-wise');
  Route::get('/contact', [WebUserController::class, 'contact'])->name('contact');
});



//To get all the routes list of web user...
Route::middleware(['auth:webuser'])->prefix('webuser')->group(function () {
  Route::get('dashboard', [WebUserController::class, 'userDashboard'])->name('dashboard');
  Route::post('/dashboard/change-push-notification-is-seen', [WebUserController::class, 'changePushNotificationUnseen'])->name('change-push-notification-is-seen');
  Route::post('/dashboard/change-unpaid-reminder-is-seen', [WebUserController::class, 'changeReminderUnseen'])->name('change-unpaid-reminder-is-seen');
  // Notification 
  Route::get('notification-data', [WebUserController::class, 'notificationUnseenAll'])->name('notification-all-data');
  
  Route::get('/about', [WebUserController::class, 'about'])->name('about');
  Route::get('/documentation-category', [WebUserController::class, 'documentationCategory'])->name('documentation-category');
  Route::get('/category-wise-documentation/{id}', [WebUserController::class, 'categoryWiseDocumentation'])->name('documentation-category-wise-documentation');
  Route::get('/user-activity-total-hit-increase', [WebUserController::class, 'userTotalHitIncrease'])->name('user-activity-total-hit-increase');
  
  //For profile route ....
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
  Route::get('/change-info', [ProfileController::class, 'changeInfo'])->name('change-info');
  Route::post('/change-info-update', [ProfileController::class, 'changeInfoPpdate'])->name('change-info-update');
  Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
  Route::post('/change-password-update', [ProfileController::class, 'changePasswordPpdate'])->name('change-password-update');
  Route::get('/change-mobile', [ProfileController::class, 'changeMobile'])->name('change-mobile');
  Route::post('/change-mobile-update', [ProfileController::class, 'changeMobileUpdate'])->name('change-mobile-update');
  Route::get('/mobile-change-verify-OTP/{user_mobile}/{user_data}', [ProfileController::class, 'getMobileChangeVerifyOtpPage'])->name('get-mobile-change-verify-OTP-page');
  Route::post('/mobile-change-verify-OTP', [ProfileController::class, 'mobileChangeVerifyOtp'])->name('mobile-change-verify-OTP');
  Route::get('/resend-Otp-profile/{mobile}/{user_mobile}',   [ProfileController::class, 'resendOtp'])->name('resend-Otp-profile');

  //For mobile wallet, banking wallet & credit card wallet account controller section...
  Route::get('mobile-wallet-account', [AccountListController::class, 'getMobileWalletAccount'])->name('mobile-wallet-account');
  Route::get('mobile-wallet-account-create', [AccountListController::class, 'createMobileWalletAccount'])->name('mobile-wallet-account-create');
  Route::post('mobile-wallet-account-store', [AccountListController::class, 'storeMobileWalletAccount'])->name('mobile-wallet-account-store');
  Route::get('mobile-wallet-account-edit-list', [AccountListController::class, 'mobileWalletAccountEditList'])->name('mobile-wallet-account-edit-list');
  Route::get('single-mobile-wallet-account-edit/{id}', [AccountListController::class, 'singleMobileWalletAccountEdit'])->name('single-mobile-wallet-account-edit');
  Route::post('single-mobile-wallet-account-update/{id}', [AccountListController::class, 'singleMobileWalletAccountUpdate'])->name('single-mobile-wallet-account-update');
  Route::get('single-mobile-wallet-account-active/{id}', [AccountListController::class, 'singleMobileWalletAccountActive'])->name('single-mobile-wallet-account-active');
  Route::get('single-mobile-wallet-account-inactive/{id}', [AccountListController::class, 'singleMobileWalletAccountInActive'])->name('single-mobile-wallet-account-inactive');


  //Bank...
  Route::get('banking-wallet-account', [AccountListController::class, 'getBankingWalletAccount'])->name('banking-wallet-account');
  Route::get('banking-wallet-account-create', [AccountListController::class, 'createBankingWalletAccount'])->name('banking-wallet-account-create');
  Route::post('banking-wallet-account-store', [AccountListController::class, 'storeBankingWalletAccount'])->name('banking-wallet-account-store');
  Route::get('banking-wallet-account-edit-list', [AccountListController::class, 'bankingWalletAccountEditList'])->name('banking-wallet-account-edit-list');
  Route::get('single-banking-wallet-account-edit/{id}', [AccountListController::class, 'singleBankingWalletAccountEdit'])->name('single-banking-wallet-account-edit');
  Route::post('single-banking-wallet-account-update/{id}', [AccountListController::class, 'singleBankingWalletAccountUpdate'])->name('single-banking-wallet-account-update');
  Route::get('single-banking-wallet-account-active/{id}', [AccountListController::class, 'singleBankingWalletAccountActive'])->name('single-banking-wallet-account-active');
  Route::get('single-banking-wallet-account-inactive/{id}', [AccountListController::class, 'singleBankingWalletAccountInActive'])->name('single-banking-wallet-account-inactive');

  //DBR Calculator and EMI Calculator
  Route::get('dbr-calculator', [WebUserController::class, 'dbrCalculator'] )->name('dbr-caculator');
  Route::get('emi-calculator', [WebUserController::class, 'emiCalculator'])->name('emi-caculator');
  Route::get('loan-calculator', [WebUserController::class, 'loanCalculator'])->name('loan-caculator');

  //Credit Card...
  Route::get('credit-card-wallet-account', [AccountListController::class, 'getCreditCardWalletAccount'])->name('credit-card-wallet-account');
  Route::get('credit-card-wallet-account-create', [AccountListController::class, 'createCreditCardWalletAccount'])->name('credit-card-wallet-account-create');
  Route::post('credit-card-wallet-account-store', [AccountListController::class, 'storeCreditCardWalletAccount'])->name('credit-card-wallet-account-store');
  Route::get('credit-card-wallet-account-edit-list', [AccountListController::class, 'creditCardWalletAccountEditList'])->name('credit-card-wallet-account-edit-list');
  Route::get('single-credit-card-wallet-account-edit/{id}', [AccountListController::class, 'singleCreditCardWalletAccountEdit'])->name('single-credit-card-wallet-account-edit');
  Route::post('single-credit-card-wallet-account-update/{id}', [AccountListController::class, 'singleCreditCardWalletAccountUpdate'])->name('single-credit-card-wallet-account-update');
  Route::get('single-credit-card-wallet-account-active/{id}', [AccountListController::class, 'singleCreditCardWalletAccountActive'])->name('single-credit-card-wallet-account-active');
  Route::get('single-credit-card-wallet-account-inactive/{id}', [AccountListController::class, 'singleCreditCardWalletAccountInActive'])->name('single-credit-card-wallet-account-inactive');

  //For Pocket, Bank & MFS  Transaction...
  Route::get('pocket-wallet-account-transaction-all-list', [TransactionController::class, 'transactionPocketWalletAccountAllList'])->name('pocket-wallet-account-transaction-all-list');
  Route::get('pocket-wallet-account-transaction-credit-list', [TransactionController::class, 'transactionPocketWalletAccountCreditList'])->name('pocket-wallet-account-transaction-credit-list');
  Route::get('pocket-wallet-account-transaction-debit-list', [TransactionController::class, 'transactionPocketWalletAccountDebitList'])->name('pocket-wallet-account-transaction-debit-list');
  //Mobile...
  Route::get('mobile-wallet-account-transaction-all-list/{id}', [TransactionController::class, 'transactionMobileWalletAccountAllList'])->name('mobile-wallet-account-transaction-all-list');
  Route::get('mobile-wallet-account-transaction-credit-list/{id}', [TransactionController::class, 'transactionMobileWalletAccountCreditList'])->name('mobile-wallet-account-transaction-credit-list');
  Route::get('mobile-wallet-account-transaction-debit-list/{id}', [TransactionController::class, 'transactionMobileWalletAccountDebitList'])->name('mobile-wallet-account-transaction-debit-list');
  //Bank...
  Route::get('banking-wallet-account-transaction-all-list/{id}', [TransactionController::class, 'transactionBankingWalletAccountAllList'])->name('banking-wallet-account-transaction-all-list');
  Route::get('banking-wallet-account-transaction-credit-list/{id}', [TransactionController::class, 'transactionBankingWalletAccountCreditList'])->name('banking-wallet-account-transaction-credit-list');
  Route::get('banking-wallet-account-transaction-debit-list/{id}', [TransactionController::class, 'transactionBankingWalletAccountDebitList'])->name('banking-wallet-account-transaction-debit-list');
  //Card...
  Route::get('card-wallet-account-transaction-all-list/{id}', [TransactionController::class, 'transactionCardWalletAccountAllList'])->name('card-wallet-account-transaction-all-list');
  Route::get('card-wallet-account-transaction-credit-list/{id}', [TransactionController::class, 'transactionCardWalletAccountCreditList'])->name('card-wallet-account-transaction-credit-list');
  Route::get('card-wallet-account-transaction-debit-list/{id}', [TransactionController::class, 'transactionCardWalletAccountDebitList'])->name('card-wallet-account-transaction-debit-list');


  //For Pocket To Bank & MFS  Transfer...
  Route::get('pocket-wallet-account-transfer/{payment_type}', [PocketAccountController::class, 'transferPocketWalletAccount'])->name('pocket-wallet-account-transfer');
  Route::post('get-account-data-with-bank-wise', [PocketAccountController::class, 'getAccountWithBankWise'])->name('get-account-data-with-bank-wise');
  Route::post('get-account-data-with-mobile-wallet-wise', [PocketAccountController::class, 'getAccountWithMobileWalletWise'])->name('get-account-data-with-mobile-wallet-wise');
  Route::post('pocket-to-bank-account-transfer', [PocketAccountController::class, 'pocketToBankTransfer'])->name('pocket-to-bank-account-transfer');
  Route::post('pocket-to-mfs-account-transfer', [PocketAccountController::class, 'pocketToMFSTransfer'])->name('pocket-to-mfs-account-transfer');
  Route::post('get-bank-wise-credit-card-data', [PocketAccountController::class, 'getBankWiseCreditCardData'])->name('get-bank-wise-credit-card-data');
  Route::post('pocket-to-card-account-transfer', [PocketAccountController::class, 'pocketToCardTransfer'])->name('pocket-to-card-account-transfer');


  //For MFS To Bank, MFS & Pocket Transfer...
  Route::get('payment-type-for-mobile-wallet-account-transfer/{id}', [MfsAccountController::class, 'paymentTypeForMobileWalletAccountTransfer'])->name('payment-type-for-mobile-wallet-account-transfer');
  Route::get('mobile-wallet-account-transfer/{account_id}/{payment_type}', [MfsAccountController::class, 'transferMobileWalletAccount'])->name('mobile-wallet-account-transfer');
  Route::post('get-mobile-wallet-wise-account-data', [MfsAccountController::class, 'getMobileWalletWiseAccountData'])->name('get-mobile-wallet-wise-account-data');
  Route::get('get-beneficiary-mobille-wallet-account-data', [MfsAccountController::class, 'getBeneficiaryMobileWalletAccountData'])->name('get-beneficiary-mobille-wallet-account-data');
  Route::post('get-account-data-bank-wise', [MfsAccountController::class, 'getAccountDataBankWise'])->name('get-account-data-bank-wise');
  Route::post('mfs-to-mfs-account-transfer/{id}', [MfsAccountController::class, 'mfsToMFSTransfer'])->name('mfs-to-mfs-account-transfer');
  Route::post('mfs-to-pocket-transfer/{id}', [MfsAccountController::class, 'mfsToPocketTransfer'])->name('mfs-to-pocket-account-transfer');
  Route::post('mfs-to-bank-transfer/{id}', [MfsAccountController::class, 'mfsToBankTransfer'])->name('mfs-to-bank-account-transfer');
  Route::get('mobile-account-details/{id}', [MfsAccountController::class, 'getSingleMobileAccountPage'])->name('mobile-account-details');


  //For Bank To Bank, MFS & Pocket Transfer...
  Route::get('payment-type-for-banking-wallet-account-transfer/{id}', [BankingAccountController::class, 'paymentTypeForBankingWalletAccountTransfer'])->name('payment-type-for-banking-wallet-account-transfer');
  Route::get('banking-wallet-account-transfer/{account_id}/{payment_type}', [BankingAccountController::class, 'transferBankingWalletAccount'])->name('banking-wallet-account-transfer');
  Route::post('get-bank-wise-account-data', [BankingAccountController::class, 'getBankWiseAccountData'])->name('get-bank-wise-account-data');
  Route::get('get-beneficiary-bank-list-data', [BankingAccountController::class, 'getBeneficiaryBankListData'])->name('get-beneficiary-bank-list-data');
  Route::get('get-beneficiary-mobile-wallet-data', [BankingAccountController::class, 'getBeneficiaryMobileWalletData'])->name('get-beneficiary-mobile-wallet-data');
  Route::post('bank-to-bank-account-transfer/{id}', [BankingAccountController::class, 'bankToBankTransfer'])->name('bank-to-bank-account-transfer');
  Route::post('bank-to-mfs-account-transfer/{id}', [BankingAccountController::class, 'bankToMFSTransfer'])->name('bank-to-mfs-account-transfer');
  Route::post('bank-to-pocket-account-transfer/{id}', [BankingAccountController::class, 'bankToPocketTransfer'])->name('bank-to-pocket-account-transfer');
  Route::get('bank-account-details/{id}', [BankingAccountController::class, 'getSingleBankAccountPage'])->name('bank-account-details');
  

  //For Credit Card To Bank & MFS Transfer...
  Route::get('credit-card-details/{id}', [CreditCardController::class, 'getSingleCreditCardPage'])->name('credit-card-details');
  Route::get('credit-card-wallet-account-transfer/{account_id}/{payment_type}', [CreditCardController::class, 'transferCreditCardWalletAccount'])->name('credit-card-wallet-account-transfer');
  Route::post('get-account-data-with-bank-wise-for-credit-card', [CreditCardController::class, 'getAccountWithBankWise'])->name('get-account-data-with-bank-wise-for-credit-card');
  Route::post('get-account-data-with-mobile-wallet-wise-for-credit-card', [CreditCardController::class, 'getAccountWithMobileWalletWise'])->name('get-account-data-with-mobile-wallet-wise-for-credit-card');
  Route::post('credit-card-to-bank-account-transfer/{id}', [CreditCardController::class, 'cardToBankTransfer'])->name('credit-card-to-bank-account-transfer');
  Route::post('credit-card-to-mfs-account-transfer/{id}', [CreditCardController::class, 'cardToMFSTransfer'])->name('credit-card-to-mfs-account-transfer');
  //For Bank, MFS, Wallet To Card Account Transfer...
  Route::get('payment-type-for-bill-payment/{id}', [CreditCardController::class, 'paymentTypeForBillPayment'])->name('payment-type-for-bill-payment');
  Route::get('credit-card-account-transfer/{account_id}/{payment_type}', [CreditCardController::class, 'transferCreditCardAccount'])->name('credit-card-account-transfer');
  Route::post('bank-to-credit-card-account-transfer/{id}', [CreditCardController::class, 'bankToCardTransfer'])->name('bank-to-credit-card-account-transfer');
  Route::post('mfs-to-credit-card-account-transfer/{id}', [CreditCardController::class, 'mfsToCardTransfer'])->name('mfs-to-credit-card-account-transfer');
  Route::post('pocket-to-credit-card-account-transfer/{id}', [CreditCardController::class, 'pocketToCardTransfer'])->name('pocket-to-credit-card-account-transfer');

  //For credit card reminder...
  Route::get('credit-card-bill-reminder/{id}', [CreditCardController::class, 'creditCardBillReminder'])->name('credit-card-bill-reminder');
  Route::get('credit-card-bill-reminder-create/{id}', [CreditCardController::class, 'creditCardBillReminderCreate'])->name('credit-card-bill-reminder-create');
  Route::post('credit-card-bill-reminder-store', [CreditCardController::class, 'storeCreditCardBillReminder'])->name('credit-card-bill-reminder-store');
  Route::get('credit-card-bill-reminder-edit/{credit_card_id}/{card_reminder_id}', [CreditCardController::class, 'creditCardBillReminderEdit'])->name('credit-card-bill-reminder-edit');
  Route::post('credit-card-bill-reminder-update', [CreditCardController::class, 'updateCreditCardBillReminder'])->name('credit-card-bill-reminder-update');
  Route::post('credit-card-bill-reminder-status-change', [CreditCardController::class, 'changeStatusCreditCardBillReminder'])->name('credit-card-bill-reminder-status-change');
  
  //For credit card currency change...
  Route::get('card-currency/{id}', [CreditCardController::class, 'cardCurrency'])->name('card-currency');
  Route::post('card-currency-enabled/{id}', [CreditCardController::class, 'enableCurrency'])->name('card-currency-enabled');
  Route::post('card-currency-disabled/{id}', [CreditCardController::class, 'disableCurrency'])->name('card-currency-disabled');

  //For Income & Expense...
  Route::get('wallet-income-create/{income_type}', [IncomeController::class, 'createIncome'])->name('wallet-income-create');
  Route::post('wallet-income-save/{income_type}', [IncomeController::class, 'saveIncome'])->name('wallet-income-save');
  Route::post('get-account-bank-wise', [IncomeController::class, 'getAccountBankWise'])->name('get-account-bank-wise');
  Route::post('get-account-mobile-wallet-wise', [IncomeController::class, 'getAccountMobileWalletWise'])->name('get-account-mobile-wallet-wise');
  //Expense...
  Route::get('wallet-expense-create/{expense_type}', [ExpenseController::class, 'createExpense'])->name('wallet-expense-create');
  Route::post('check-dual-currency-with-card-id', [ExpenseController::class, 'checkDualCurrency'])->name('check-dual-currency-with-card-id');
  Route::post('wallet-expense-save/{expense_type}', [ExpenseController::class, 'saveExpense'])->name('wallet-expense-save');


  //bank Beneficiary and mfs Beneficiary 
  Route::get('bank-beneficiary', [BeneficiaryController::class, 'bankBeneficiary'])->name('bank-beneficiary');
  Route::get('banking-beneficiary-account-create', [BeneficiaryController::class, 'bankingBeneficiaryAccountCreate'])->name('banking-beneficiary-account-create');
  Route::post('banking-beneficiary-account-store', [BeneficiaryController::class, 'bankingBeneficiaryAccountStore'])->name('banking-beneficiary-account-store');

  Route::get('mfs-beneficiary', [BeneficiaryController::class, 'mfsBeneficiary'])->name('mfs-beneficiary');
  Route::get('mfs-beneficiary-account-create', [BeneficiaryController::class, 'mfsBeneficiaryAccountCreate'])->name('mfs-beneficiary-account-create');
  Route::post('mfs-beneficiary-account-store', [BeneficiaryController::class, 'mfsBeneficiaryAccountStore'])->name('mfs-beneficiary-account-store');


  //invoice .............
  Route::get('transaction-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'tranjectionInvoice'])->name('tranjection-invoice');
  Route::get('transactions-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'tranjectionInvoicePocketWallet'])->name('tranjections-invoice');
  Route::get('wallet-transaction-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'walletTranjectionInvoice'])->name('wallet-tranjection-invoice');
  Route::get('card-transaction-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'cardTranjectionInvoice'])->name('card-tranjection-invoice');
  Route::get('card-bill-payment-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'cardBillPaymentInvoice'])->name('card-bill-payment-invoice');
  Route::get('card-wallet-bill-payment-invoice/{result}/{from_account}/{to_account}', [AccountListController::class, 'cardWalletBillPaymentInvoice'])->name('card-wallet-bill-payment-invoice');
  Route::get('income-invoice/{result}/{from_account}', [AccountListController::class, 'incomeInvoice'])->name('income-invoice');
  Route::get('income-wallet-invoice/{result}/{from_account}', [AccountListController::class, 'incomeWalletInvoice'])->name('income-wallet-invoice');
  Route::get('expense-invoice/{result}/{from_account}', [AccountListController::class, 'expenseInvoice'])->name('expense-invoice');
  Route::get('expense-card-invoice/{result}/{from_account}', [AccountListController::class, 'expenseCardInvoice'])->name('expense-card-invoice');
  Route::get('expense-wallet-invoice/{result}/{from_account}', [AccountListController::class, 'expenseWalletInvoice'])->name('expense-wallet-invoice');


  //revert route.....
  Route::get('transfer-revert', [AccountListController::class, 'transferRevert'])->name('transfer-revert');
  Route::post('transfer-revert-filter', [AccountListController::class, 'transferRevertFilter'])->name('transfer-revert-filter');
  Route::get('transfer-revert-update/{id}', [AccountListController::class, 'transferRevertUpdate'])->name('transfer-revert-update');

  Route::get('income-expense-revert', [AccountListController::class, 'incomeExpenseRevert'])->name('income-expense-revert');
  Route::post('income-expense-revert-filter', [AccountListController::class, 'incomeExpenseRevertFilter'])->name('income-expense-revert-filter');
  Route::get('income-expense-revert-update/{id}', [AccountListController::class, 'incomeExpenseRevertUpdate'])->name('income-expense-revert-update');
 
  //For user logout...
  Route::get('logout', [WebUserController::class, 'webUserLogout'])->name('logout');

});

Route::fallback(function () {
    return view('frontend.error.404');
});



