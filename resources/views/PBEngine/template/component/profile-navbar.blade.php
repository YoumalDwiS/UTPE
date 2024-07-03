<div class="be-right-navbar">
    <ul class="nav navbar-nav float-right be-user-nav">

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">
                <div class="avatar">
                    <span
                        class="avatar-title rounded-circle border border-white bg-{{ FunctionHelper::getUserColor() }}">{{ FunctionHelper::getUserInitial() }}</span>
                </div>
            </a>
            <div class="dropdown-menu" role="menu">
                <div class="user-info">
                    <div class="user-name text-black-50">{{ Auth::user()->name }}</div>
                    <div class="user-position online text-black-50">{{ Auth::user()->title }}</div>
                </div>
                <!-- <a class="dropdown-item" href="{{ LinkHelper::SATRIA_URL() }}">
                    <i class="fa-solid fa-house mr-2"></i>Go to Webportal
                </a> -->
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: one;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>

    </ul>
    <!-- <ul class="nav navbar-nav float-right be-icons-nav">
        <li class="nav-item dropdown">
            <a id="dropdown-notification" class="nav-link dropdown-toggle my-auto" href="#" data-toggle="dropdown"
                role="button" aria-expanded="false">
                <i class="fa-solid fa-bell fa-xl text-white"></i>
                @if ($data['count_notification'] > 0)
                    <span class="badge badge-danger">{{ $data['count_notification'] }}</span>
                @endif
            </a>
            <ul class="dropdown-menu be-notifications">
                <li>
                    <div class="title">Notifications<span
                            class="badge badge-pill">{{ $data['count_notification'] }}</span></div>
                    <div class="list">
                        <div class="be-scroller-notifications">
                            <div class="content">
                                @if ($data['count_notification'] > 0)
                                    <ul>

                                        @foreach ($data['notification'] as $notif)
                                            <li class="notification notification-unread">
                                                <a href="{{ url('notification/detail/') . '/' . $notif->id }}">
                                                    <div class="image">
                                                        <i class="fa-solid fa-clipboard fa-2x text-white mt-1"></i>
                                                    </div>
                                                    <div class="notification-info">
                                                        <div class="text">
                                                            <span class="user-name">{{ $notif->type }}</span>
                                                            {{ $notif->remark }}
                                                        </div>
                                                        <span
                                                            class="date">{{ Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                @else
                                    <div class="text-center my-auto">
                                        <span class="mx-auto text-bold">There is no new notification</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="footer"> <a href="{{ url('notification') }}">View all notifications</a></div>
                </li>
            </ul>
        </li>

    </ul> -->
</div>
