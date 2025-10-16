<div class="bottom_nav pt-2 pr-2 pl-2 pb-0 ">
    <div class="d-flex justify-content-center text-center">
        <a href="{{route('webuser.dashboard')}}" class="col">
            <div class="menu_item 
                {{ request()->is('webuser/dashboard') ? 'active' : '' }}
                {{ request()->is('webuser/pocket-wallet-account-transaction-all-list') ? 'active' : '' }}
                {{ request()->is('webuser/pocket-wallet-account-transaction-credit-list') ? 'active' : '' }}
                {{ request()->is('webuser/pocket-wallet-account-transaction-debit-list') ? 'active' : '' }}
                {{ request()->is('webuser/pocket-wallet-account-transfer/*') ? 'active' : '' }}
                {{ request()->is('webuser/wallet-income-create/*') ? 'active' : '' }}
                {{ request()->is('webuser/wallet-expense-create/*') ? 'active' : '' }}
                {{ request()->is('webuser/bank-beneficiary') ? 'active' : '' }}
                {{ request()->is('webuser/banking-beneficiary-account-create') ? 'active' : '' }}
                {{ request()->is('webuser/mfs-beneficiary') ? 'active' : '' }}
                {{ request()->is('webuser/mfs-beneficiary-account-create') ? 'active' : '' }}
                {{ request()->is('webuser/documentation-category') ? 'active' : '' }}
                {{ request()->is('webuser/category-wise-documentation/*') ? 'active' : '' }}
                {{ request()->is('webuser/notification-data') ? 'active' : '' }}
                {{ request()->is('webuser/about') ? 'active' : '' }}
                {{ request()->is('webuser/dbr-calculator') ? 'active' : '' }}
                {{ request()->is('webuser/emi-calculator') ? 'active' : '' }}
                {{ request()->is('webuser/loan-calculator') ? 'active' : '' }}
            ">
                <i class="fa-solid fa-house"></i>
                <p>Home</p>
            </div>
        </a>

        <a href="{{route('webuser.mobile-wallet-account')}}" class="col">
            <div class="menu_item
                {{ request()->is('webuser/mobile-wallet-account') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-wallet-account-create') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-wallet-account-transaction-all-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-wallet-account-transaction-credit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-wallet-account-transaction-debit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-account-details/*') ? 'active' : '' }}
                {{ request()->is('webuser/payment-type-for-mobile-wallet-account-transfer/*') ? 'active' : '' }}
                {{ request()->is('webuser/mobile-wallet-account-transfer/*') ? 'active' : '' }}
            ">
                <i class="fa-solid fa-mobile-android-alt"></i>
                <p>MFS</p>
            </div>
        </a>
        
        <a href="{{route('webuser.banking-wallet-account')}}" class="col">
            <div class="menu_item
                {{ request()->is('webuser/banking-wallet-account') ? 'active' : '' }}
                {{ request()->is('webuser/banking-wallet-account-transaction-all-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/banking-wallet-account-transaction-credit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/banking-wallet-account-transaction-debit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/banking-wallet-account-create') ? 'active' : '' }}
                {{ request()->is('webuser/bank-account-details/*') ? 'active' : '' }}
                {{ request()->is('webuser/payment-type-for-banking-wallet-account-transfer/*') ? 'active' : '' }}
                {{ request()->is('webuser/banking-wallet-account-transfer/*') ? 'active' : '' }}
            ">
                <i class="fa-solid fa-building-columns"></i>
                <p>Bank</p>
            </div>
        </a>

        <a href="{{route('webuser.credit-card-wallet-account')}}" class="col">
            <div class="menu_item
                {{ request()->is('webuser/credit-card-wallet-account') ? 'active' : '' }}
                {{ request()->is('webuser/card-wallet-account-transaction-all-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/card-wallet-account-transaction-credit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/card-wallet-account-transaction-debit-list/*') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-wallet-account-create') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-details/*') ? 'active' : '' }}
                {{ request()->is('webuser/payment-type-for-bill-payment/*') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-account-transfer/*') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-wallet-account-transfer/*') ? 'active' : '' }}
                {{ request()->is('webuser/card-transaction-invoice/*') ? 'active' : '' }}
                {{ request()->is('webuser/card-bill-payment-invoice/*') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-bill-reminder/*') ? 'active' : '' }}
                {{ request()->is('webuser/credit-card-bill-reminder-create/*') ? 'active' : '' }}
                {{ request()->is('webuser/card-currency/*') ? 'active' : '' }}
            ">
                {{-- <i class="fa-brands fa-cc-visa"></i> --}}
                <i class="fa-brands fa fa-credit-card"></i>
                <p>Card</p>
            </div>
        </a>

        <a href="{{route('webuser.profile')}}" class="col">
            <div class="menu_item 
            {{ request()->is('webuser/profile') ? 'active' : '' }} 
            {{ request()->is('webuser/change-info') ? 'active' : '' }} 
            {{ request()->is('webuser/change-mobile') ? 'active' : '' }} 
            {{ request()->is('webuser/change-password') ? 'active' : '' }} 
            {{ request()->is('webuser/banking-wallet-account-edit-list') ? 'active' : '' }} 
            {{ request()->is('webuser/single-banking-wallet-account-edit/*') ? 'active' : '' }} 
            {{ request()->is('webuser/mobile-wallet-account-edit-list') ? 'active' : '' }} 
            {{ request()->is('webuser/single-mobile-wallet-account-edit/*') ? 'active' : '' }} 
            {{ request()->is('webuser/credit-card-wallet-account-edit-list') ? 'active' : '' }} 
            {{ request()->is('webuser/single-credit-card-wallet-account-edit/*') ? 'active' : '' }} 
            {{ request()->is('webuser/transfer-revert') ? 'active' : '' }} 
            {{ request()->is('webuser/income-expense-revert') ? 'active' : '' }} 
            ">
                <i class="fa-solid fa-user"></i>
                <p>Profile</p>
            </div>
        </a>


    </div>
</div>