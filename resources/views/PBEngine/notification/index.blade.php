@extends('PBEngine/template/vertical', [
    'title' => 'Notification',
    'breadcrumbs' => ['Notification', 'All Notification'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">All Notification</span></div>
        <div class="card-body container">
            <div class="be-notifications h-100">
                <div class="list">
                    <div class="">
                        <div class="content">
                            @if (count($data['notification']) > 0)
                                <div>
                                    @foreach ($data['notification'] as $notif)
                                        <div class="notification {{ $notif->unread ? 'notification-unread' : '' }}">
                                            <a href="{{ url('notification/detail/') . '/' . $notif->id }}">
                                                <div class="image">
                                                    <i class="fa-solid fa-clipboard fa-2x text-white mt-1"></i>
                                                </div>
                                                <div class="notification-info">
                                                    <div class="text">
                                                        <span class="user-name">{{ $notif->type }}</span>
                                                        {{ $notif->remark }}
                                                        @if (count($notif->reader) > 0)
                                                            <div class="mt-2">
                                                                Read by
                                                                <ul class="">
                                                                    {{ dd($notif) }}
                                                                    @foreach ($notif->reader as $r)
                                                                        <li class="">
                                                                            {{ $r->user->name . ' at ' . Carbon\Carbon::parse($r->readed_at)->diffForHumans() }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <span
                                                        class="date">{{ Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center my-auto">
                                    <span class="mx-auto text-bold">There is no notification</span>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {});
    </script>
@endsection
