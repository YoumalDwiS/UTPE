@extends('PBEngine/template/vertical', [
    'title' => 'Panduan',
    'breadcrumbs' => ['Panduan'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Panduan</span></div>
        <div class="card-body">
            <ul class="timeline">
                @foreach ($data['onboarding_module'] as $om)
                    <li class="timeline-item">
                        <div class="timeline-date"><span>{{ $om->name }}</span></div>
                        <div class="timeline-content"
                            @if ($om->status == 0) disabled data-toggle='tooltip' data-placement='bottom' title='coming soon' @endif>
                            {{-- <div class="timeline-avatar"><img class="circle" src="assets/img/avatar6.png" alt="Avatar"></div> --}}
                            <div class="timeline-header">
                                <span class="timeline-time">{{ $om->done_at }}</span>
                                <span class="timeline-autor">{{ $om->name }}</span>
                                <p class="timeline-activity">{{ $om->description }}</p>
                                <br>
                                <a href="{{ url($om->url) . '?onboarding=1' }}"
                                    class="btn btn-lg btn-primary {{ $om->status ? '' : 'disabled' }}">
                                    <i class="fa-solid fa-check"></i>
                                    Do Tutorial
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <x-loading-screen />
@endsection

@section('script')
    <script>
        var table;

        $(document).ready(function() {});
    </script>
@endsection
