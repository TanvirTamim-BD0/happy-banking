<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8 rounded">

                <li class="nav-item {{ request()->is('user-profile') ? 'active' : '' }}">
                    <a class="nav-link text-active-primary" href="{{route('user-profile')}}" id="head">Personal Details</a>
                </li>

                <li class="nav-item {{ request()->is('user-security') ? 'active' : '' }}">
                    <a class="nav-link text-active-primary" href="{{route('user-security')}}" id="head">Password Change</a>
                </li>
            </ul>