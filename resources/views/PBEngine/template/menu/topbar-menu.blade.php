<div class="navbar-collapse collapse" id="be-navbar-collapse">
    <ul class="navbar-nav">
        @foreach ($data['datamenu'] as $key => $main)
            @if ($main['main'] == null)
                <li class="nav-item">
                    @foreach ($data['datamenu'][$key]['menu'] as $menu)
                        <a class="nav-link text-white" href="{{ url($menu['menu_link']) }}" menu="{{ $menu['app_menu'] }}">
                            <i class="text-white mr-2 {{ $main['icon'] }}"></i>
                            {{ $menu['app_menu'] }}
                        </a>
                    @endforeach
                </li>
            @else
                <li class="nav-item dropdown" main-menu="{{ $main['main'] }}">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" role="button"
                        aria-expanded="false">
                        <i class="text-white mr-2 {{ $main['icon'] }}"></i>
                        {{ $main['main'] }}
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        @foreach ($data['datamenu'][$key]['menu'] as $menu)
                            <a class="dropdown-item " href="{{ url($menu['menu_link']) }}"
                                menu="{{ $menu['app_menu'] }}">{{ $menu['app_menu'] }}</a>
                        @endforeach
                    </div>
                </li>
            @endif
        @endforeach

        <!-- <li class="nav-item">
            <a class="nav-link text-white" href="{{ url('onboarding') }}" menu="Panduan">
                <i class="fa-solid fa-question"></i>
                <span class="ml-3">Panduan</span>
            </a>
        </li> -->

        {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                role="button" aria-expanded="false">Remnant <i class="fa-solid fa-chevron-down"></i></a>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="form-elements.html">Dashboard</a>
                <a class="dropdown-item" href="form-elements.html">Remnant List</a>
                <a class="dropdown-item" href="form-elements.html">Penyimpanan</a>
                <a class="dropdown-item" href="form-validation.html">Pengambilan</a>
                <a class="dropdown-item" href="form-wizard.html">Pemindahan</a>
                <a class="dropdown-item" href="form-wysiwyg.html">Inventory</a>
            </div>
        </li> --}}
    </ul>
</div>
