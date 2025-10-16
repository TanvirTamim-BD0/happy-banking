@extends('frontend.master')
@section('title') Transaction Pocket Wallet Account list @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container ">
    <div class="custom-transfer-acccount-tab gap-2 btn-no-color">
        <button class="primary_btn nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
            type="button" role="tab" aria-controls="nav-home" aria-selected="true">All</button>
        <button class="primary_btn nav-link" id="nav-credit-tab" data-bs-toggle="tab" data-bs-target="#nav-credit"
            type="button" role="tab" aria-controls="nav-credit" aria-selected="true">Credit</button>
        <button class="primary_btn nav-link " id="nav-debit-tab" data-bs-toggle="tab" data-bs-target="#nav-debit"
            type="button" role="tab" aria-controls="nav-debit" aria-selected="false">Debit</button>
    </div>

    

    <div class="tab-content mt-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            @foreach ($allData as $singleTtemForAll)
                @if(isset($singleTtemForAll) && $singleTtemForAll != null)
                    <div class="card transaction p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between p-2">
                            <div class="d-flex flex-column">
                                <h6>{{$singleTtemForAll['title']}}</h6>
                                <p class="text-secondary fw-light mb-0"><b>Acc:</b> {{$singleTtemForAll['account_number']}}</p>
                                <p class="text-secondary fw-light mb-0"><b>Cr/Dr:</b> {{$singleTtemForAll['credit_debit_amount']}}Tk</p>
                            </div>
                            <div class="d-flex flex-column custom-credit-debit-date-time">
                                <p class="text-secondary fw-light"><b>Date:</b> {{$singleTtemForAll['date']}} <br><b>Time:</b>
                                    {{$singleTtemForAll['time']}}</p>
                            </div>
                        </div>
                        <div class="bg-secondary p-2">
                            <p class="h5 m-0">PWC: Pocket wallet credit thanks. </p>
                        </div>
                    </div>
                @endif
            @endforeach

            @if($allData->count() > 0)
                <span class="custom-banking-pagination">
                    {{ $allData->links() }}
                </span>
            @endif
        </div>
    
        <!-- All the credit transfer data -->
        <div class="tab-pane fade" id="nav-credit" role="tabpanel" aria-labelledby="nav-credit-tab">
            @foreach ($creditData as $item)
                @if(isset($item) && $item != null)
                    <div class="card transaction p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between p-2">
                            <div class="d-flex flex-column">
                                <h6>{{$item['title']}}</h6>
                                <p class="text-secondary fw-light mb-0"><b>Acc:</b> {{$item['account_number']}}</p>
                                <p class="text-secondary fw-light mb-0"><b>Credit:</b> {{$item['credit_amount']}}Tk</p>
                            </div>
                            <div class="d-flex flex-column custom-credit-debit-date-time">
                                <p class="text-secondary fw-light"><b>Date:</b> {{$item['date']}} <br><b>Time:</b> {{$item['time']}}</p>
                            </div>
                        </div>
                        <div class="bg-secondary p-2">
                            <p class="h5 m-0">PWC: Pocket wallet credit thanks. </p>
                        </div>
                    </div>
                @endif
            @endforeach

            @if($creditData->count() > 0)
                <span class="custom-banking-pagination">
                    {{ $creditData->links() }}
                </span>
            @endif
        </div>

        <!-- All the debit transfer data -->
        <div class="tab-pane fade" id="nav-debit" role="tabpanel" aria-labelledby="nav-debit-tab">
            @foreach ($debitData as $singleTtemForDebit)
                @if(isset($singleTtemForDebit) && $singleTtemForDebit != null)
                    <div class="card transaction p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between p-2">
                            <div class="d-flex flex-column">
                                <h6>{{$singleTtemForDebit['title']}}</h6>
                                <p class="text-secondary fw-light mb-0"><b>Acc:</b> {{$singleTtemForDebit['account_number']}}</p>
                                <p class="text-secondary fw-light mb-0"><b>Debit:</b> {{$singleTtemForDebit['debit_amount']}}Tk</p>
                            </div>
                            <div class="d-flex flex-column custom-credit-debit-date-time">
                                <p class="text-secondary fw-light"><b>Date:</b> {{$singleTtemForDebit['date']}} <br><b>Time:</b> {{$singleTtemForDebit['time']}}</p>
                            </div>
                        </div>
                        <div class="bg-secondary p-2">
                            <p class="h5 m-0">PWC: Pocket wallet credit thanks. </p>
                        </div>
                    </div>
                @endif
            @endforeach

            @if($debitData->count() > 0)
                <span class="custom-banking-pagination">
                    {{ $debitData->links() }}
                </span>
            @endif
        </div>
    </div>


</div>

@endsection