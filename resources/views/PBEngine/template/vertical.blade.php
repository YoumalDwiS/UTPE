<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href={{ asset('public/assets/img/logo/logo-pb.png') }}>
    <title>PB Engine {{ isset($title) ? '| ' . $title : '' }}</title>

    @include('PBEngine/template/asset/style-area')

    @yield('style')

</head>

<body>
    <div class="be-wrapper be-collapsible-sidebar be-collapsible-sidebar-hide-logo">

        {{-- TOPBAR --}}
        <nav class="navbar navbar-expand fixed-top be-top-header bg-{{ FunctionHelper::getUserColor() }}">
            <div class="container-fluid">
                <div class="be-navbar-header">
                    <a class="navbar-brand">
                        <img src="{{ asset('public/assets/img/logo/pbwhite.png') }}" alt="logo" class="img-fluid">
                    </a>
                    <a class="be-toggle-left-sidebar">
                        <i class="fa-solid fa-bars text-white"></i>
                    </a>
                </div>
                <div class="page-title text-white"><span>{{ isset($title) ? $title : '' }}</span></div>

                @include('PBEngine/template/component/profile-navbar')

            </div>
        </nav>

        {{-- SIDEBAR --}}

        @include('PBEngine/template/menu/sidebar-menu')

        {{-- CONTENT --}}

        <div class="be-content">
            <div class="page-head">
                <h2 class="page-head-title">{{ isset($title) ? $title : '' }}</h2>

                @isset($breadcrumbs)
                    <div class="page-title-box pb-0">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb page-head-nav">
                                @foreach ($breadcrumbs as $item)
                                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}"><a
                                            href="#">{{ $item }}</a></li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                @endisset

            </div>
            <div class="main-content container-fluid">

                @yield('content')

            </div>
        </div>
    </div>

    @include('PBEngine/template/asset/script-area')

    @yield('script')

</body>

</html>
