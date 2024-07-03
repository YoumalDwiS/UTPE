<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a class="left-sidebar-toggle" href="#"></a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">

                        {{-- <li class="divider">PB Engine Phase 2</li>

                        <li>
                            <a href="index.html">
                                <i class="fa-solid fa-house"></i>
                                <span class="ml-3">Homepage</span>

                            </a>
                        </li> --}}

                        <li class="divider">PB Engine Phase 3</li>

                        @foreach ($data['datamenu'] as $key => $main)
                            @if ($main['main'] == null)
                                @foreach ($data['datamenu'][$key]['menu'] as $menu)
                                    <li menu="{{ $menu['app_menu'] }}">
                                        <a href="{{ url($menu['menu_link']) }}">
                                            <i class="{{ $main['icon'] }}"></i>
                                            <span class="ml-3">{{ $menu['app_menu'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li class="parent" main-menu="{{ $main['main'] }}">
                                    <a href="#">
                                        <i class="{{ $main['icon'] }}"></i>
                                        <span class="ml-3 ">{{ $main['main'] }}</span>
                                    </a>

                                    <ul class="sub-menu">
                                        @foreach ($data['datamenu'][$key]['menu'] as $menu)
                                            <li class="{{ !strcmp(Request::path(), $menu['menu_link']) ? 'active' : '' }}"
                                                menu="{{ $menu['app_menu'] }}">
                                                <a href="{{ url($menu['menu_link']) }}">{{ $menu['app_menu'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </li>
                            @endif
                        @endforeach

                        <!-- <li menu="Panduan">
                            <a href="{{ url('onboarding') }}">
                                <i class="fa-solid fa-question"></i>
                                <span class="ml-3">Panduan</span>
                            </a>
                        </li> -->

                    </ul>

                    {{-- <div class="progress-widget">
              <div class="progress-data"><span class="progress-value">60%</span><span class="name">Current Project</span></div>
              <div class="progress">
                <div class="progress-bar progress-bar-primary" style="width: 60%;"></div>
              </div>
            </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
