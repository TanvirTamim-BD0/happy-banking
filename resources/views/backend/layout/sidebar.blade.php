
<nav>

    <ul class="left_nav">
        <li><a href="{{route('home')}}"><i class="fa fa-house"></i> <span>Dashboard</span></a></li>

        <li>
            <a id="icon_4" onclick="toggleCard('post_sub_menu4','icon_4')" class="dropdown-toggle post_toggle
                {{ request()->is('mobile-wallet') ? '' : 'active' }}
            " 
            href="javascript:void(0)"><i class="fas fa-wallet"></i> <span>Mobile Wallet</span></a>
            <ul id="post_sub_menu4" class="toggle_sub_menu
                {{ request()->is('mobile-wallet') ? 'submenu-active' : '' }}
                "
                >
                <li class="circle">
                    <a class="" href="{{route('mobile-wallet.index')}}"><span>All Wallet</span></a>
                </li>

            </ul>
        </li>

        <li>
            <a id="icon_3" onclick="toggleCard('post_sub_menu1','icon_3')" class="dropdown-toggle post_toggle 
                {{ request()->is('banks') ? '' : 'active' }}
            "
                href="javascript:void(0)"><i class="fa fa-bank"> </i> <span>Banks</span></a>
            <ul id="post_sub_menu1" class="toggle_sub_menu 
                {{ request()->is('banks') ? 'submenu-active' : '' }}
            ">

                <li class="circle"><a class="" href="{{route('banks.index')}}"><span>All Bank</span></a></li>

            </ul>
        </li>

        <li><a id="icon_5" onclick="toggleCard('post_sub_menu5','icon_5')" class="dropdown-toggle post_toggle 
                {{ request()->is('transaction-category') ? '' : 'active' }}
            "
                href="javascript:void(0)"><i class="fas fa-list"></i> <span>Transaction Cat</span></a>
            <ul id="post_sub_menu5" class="toggle_sub_menu 
                {{ request()->is('transaction-category') ? 'submenu-active' : '' }}
            ">

                <li class="circle"><a class="" href="{{route('transaction-category.index')}}"><span>All
                            Category</span></a></li>

            </ul>
        </li>

        <li><a id="icon_2" onclick="toggleCard('post_sub_menu2','icon_2')" class="dropdown-toggle post_toggle 
                {{ request()->is('active-session') ? '' : 'active' }}
            "
                href="javascript:void(0)"><i class="fas fa-calendar-check"></i> <span>Active Session</span></a>
            <ul id="post_sub_menu2" class="toggle_sub_menu 
                {{ request()->is('active-session') ? 'submenu-active' : '' }}
            ">

                <li class="circle"><a class="" href="{{route('active-session.index')}}"><span>All Session</span></a>
                </li>

            </ul>
        </li>

        
        <li><a id="icon_1" onclick="toggleCard('post_sub_menu','icon_1')" class="dropdown-toggle post_toggle 
            {{ request()->is('blog*') ? '' : 'active' }}
            "
            href="javascript:void(0)"><i class="fa fa-book"> </i> <span>Blog</span></a>
            <ul id="post_sub_menu" class="toggle_sub_menu 
            {{ request()->is('blog*') ? 'submenu-active' : '' }}
            ">
            
            <li class="circle"><a class="" href="{{route('blog-category.index')}}"><span>Category List</span></a>
            </li>
            
            <li class="circle"><a class="" href="{{route('blog.index')}}"><span>Blog List</span></a></li>
            
        </ul>
    </li>
    
    <li><a id="icon_8" onclick="toggleCard('post_sub_menu_documentation8','icon_8')" class="dropdown-toggle post_toggle 
            {{ request()->is('blog*') ? '' : 'active' }}
        "
            href="javascript:void(0)"><i class="fa-solid fa-file-lines"></i><span>Documentation</span></a>
        <ul id="post_sub_menu_documentation8" class="toggle_sub_menu 
            {{ request()->is('blog*') ? 'submenu-active' : '' }}
        ">

            <li class="circle"><a class="" href="{{route('documentation-category.index')}}"><span>Category List</span></a>
            </li>

            <li class="circle"><a class="" href="{{route('documentation.index')}}"><span>Documentation List</span></a></li>

        </ul>
    </li>

    <li><a id="icon_9" onclick="toggleCard('post_sub_menu_documentation9','icon_9')" class="dropdown-toggle post_toggle 
            {{ request()->is('blog*') ? '' : 'active' }}"
            href="javascript:void(0)"><i class="fa-solid fa-money-check-dollar"></i><span>Payment Type</span></a>
        <ul id="post_sub_menu_documentation9" class="toggle_sub_menu 
            {{ request()->is('blog*') ? 'submenu-active' : '' }}">

            <li class="circle"><a class="" href="{{route('payment-type.index')}}"><span>Payment List</span></a>
            </li>

        </ul>
    </li>

    <li><a id="icon_10" onclick="toggleCard('post_sub_menu_documentation10','icon_10')" class="dropdown-toggle post_toggle 
            {{ request()->is('blog*') ? '' : 'active' }}"
            href="javascript:void(0)"><i class="fa-regular fa-note-sticky"></i><span>Frontend Note</span></a>
        <ul id="post_sub_menu_documentation10" class="toggle_sub_menu 
            {{ request()->is('blog*') ? 'submenu-active' : '' }}">

            <li class="circle"><a class="" href="{{route('frontend-note.index')}}"><span>Note List</span></a>
            </li>

        </ul>
    </li>
    
    <li><a id="icon_6" onclick="toggleCard('post_sub_menu6','icon_6')" class="dropdown-toggle post_toggle 
                {{ request()->is('profession*') ? '' : 'active' }}
            "
                href="javascript:void(0)"><i class="fa-solid fa-chalkboard-user"></i> <span>Profession</span></a>
            <ul id="post_sub_menu6" class="toggle_sub_menu 
                {{ request()->is('profession*') ? 'submenu-active' : '' }}
            ">

                <li class="circle"><a class="" href="{{route('profession.index')}}"><span>Profession List</span></a></li>

            </ul>
        </li>
        
        <li><a id="icon_7" onclick="toggleCard('post_sub_menu7','icon_7')" class="dropdown-toggle post_toggle 
                {{ request()->is('push-notification') ? '' : 'active' }}
                {{ request()->is('push-notification/*') ? '' : 'active' }}
            "
                href="javascript:void(0)"><i class="fa-solid fa-bell pointer-event"></i> <span>Push Notification</span></a>
            <ul id="post_sub_menu7" class="toggle_sub_menu 
                {{ request()->is('push-notification') ? 'submenu-active' : '' }}
                {{ request()->is('push-notification/*') ? 'submenu-active' : '' }}
            ">

                <li class="circle"><a class="" href="{{route('push-notification.index')}}"><span>Push Notification</span></a></li>

            </ul>
        </li>

        <li><a href="{{route('users.index')}}" href="javascript:void(0)"><i class="fa fa-users"> </i>
                <span>User</span></a>
        </li>


        <li class="collapse" onclick="toggleClass('left_side','content')"><a href="javascript:void(0)"><i
                    class="fa fa-play"></i> <span>Collapse</span></a></li>
    </ul>
</nav>