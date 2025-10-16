            <div class="col-md-3 mb-2">

              <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8 rounded">

                <li style="
                padding: 13px;
            " class="fw-bold fs-4">Profile</li>
               
            </ul>
              <div class="leads rounded">
                <div class="leads_profile text-center mb-3">

                  @if(isset($singleUser->image))
                  <img src="{{ asset('backend/uploads/userProfile/'. $singleUser->image) }}" alt="profile leads">
                  @else
                  <img src="{{asset('backend')}}/uploads/userProfile/default.png" alt="profile leads">
                  @endif

                  <a href="#" class="text-gray-800">{{ $singleUser->name}}</a>
                </div>
                

                <div class="leads_user_info">
                  <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold rotate collapsible active" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="true" aria-controls="kt_user_view_details">Details
                    <span class="ms-2 rotate-180">
                        <span class="svg-icon svg-icon-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </span></div>

                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" data-kt-initialized="1">
                        
                    </span>

                </div>
                <div class="separator"></div>

                <div id="kt_user_view_details" class="collapse show" style="">
                  <div class="pb-5 fs-6">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold mt-3">Name</div>
                      <div class="text-gray-600">{{ $singleUser->name}}</div>
                      </div>

                      <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold mt-3">Email</div>
                      <div class="text-gray-600">{{ $singleUser->email}}</div>
                      </div>

                      <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold mt-3">Mobile</div>
                      <div class="text-gray-600">
                          <a href="tel:01774444002" class="text-gray-600 text-hover-primary">{{ $singleUser->mobile}}</a>
                      </div>
                      </div>
                      
                  </div>
              </div>
                </div>
              </div>
            </div>