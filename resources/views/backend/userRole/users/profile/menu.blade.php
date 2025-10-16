<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8 rounded custom-user-activity-form">
    <form class="gy-1 pt-75 w-100" action="{{route('users-activity-filter', $singleUser->id)}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row p-2">
            <div class="col-md-4 mb-2 custom-select2-dropdown">
                <div class="single_input">
                    <label for="from_date" class="fw-bolder mb-1">From Date <span
                            class="text-danger custom-required-font">(Required)</span></label>
                    <input placeholder="From Date"
                        class="form-control" type="date" name="from_date" id="from_date" required>
        
                    @error('from_date')
                    <span class=text-danger>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-4 mb-2 custom-select2-dropdown">
                <div class="single_input">
                    <label for="to_date" class="fw-bolder mb-1">To Date <span
                            class="text-danger custom-required-font">(Required)</span></label>
                    <input placeholder="To Date"
                        class="form-control" type="date" name="to_date" id="to_date" required>
        
                    @error('to_date')
                    <span class=text-danger>{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 mb-2 custom-select2-dropdown">
                <div class="single_input">
                    <input class="form-control btn btn-primary custom-user-activity-filter-btn" type="submit" value="Filter Data"
                        required>
                </div>
            </div>
        
        </div>

    </form>

</ul>