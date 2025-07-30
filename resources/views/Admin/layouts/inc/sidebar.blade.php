<!--begin::Aside-->
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="black" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="black" />
                </svg>
            </span>
        </div>
    </div>
    <!--end::Brand-->

    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <div class="pb-5 hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">

                <!-- Dashboard -->
                <div class="menu-item">
                    <a class="menu-link menu-link-active" href="{{ route('admin.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Default') }}</span>
                    </a>
                </div>

                <!-- User Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Users') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show users'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('users.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Users') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show admins'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('admins.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Admins') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show roles'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('roles.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Roles') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Content Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Content') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show countries'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('countries.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.countries') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show cities'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('cities.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.cities') }}</span>
                            </a>
                        </div>
                        @endif

                        @if (auth('admin')->user()->can('show areas'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('area.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.areas') }}</span>
                            </a>
                        </div>
                        @endif

                        @if (auth('admin')->user()->can('show vars'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('vars.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.vars') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show predicts'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('predicts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.predicts') }}</span>
                            </a>
                        </div>
                        @endif

                        {{-- @if (auth('admin')->user()->can('show clubs'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('clubs.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.clubs') }}</span>
                            </a>
                        </div>
                        @endif --}}
                        {{-- @if (auth('admin')->user()->can('show players'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('players.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.players') }}</span>
                            </a>
                        </div>
                        @endif --}}
                         @if (auth('admin')->user()->can('show votes'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('votes.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.votes') }}</span>
                            </a>
                        </div>
                        @endif

                         @if (auth('admin')->user()->can('show vote-choices'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('vote-choices.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.vote-choices') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show academy'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('academy.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.academy') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show user-coach'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('user-coach.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.user-coach') }}</span>
                            </a>
                        </div>
                        @endif
                        {{-- @if (auth('admin')->user()->can('show varChooses'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('varChooses.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.varChooses') }}</span>
                            </a>
                        </div>
                        @endif --}}
                    </div>
                </div>

                <!-- Products -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Manage Products') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show categoryProducts'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('categoryProducts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.category Products') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show products'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('products.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Products') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @if (auth('admin')->user()->can('show talants'))

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Manage Talents') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('talants.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.talants') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('talants.index') }}?status=1">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Requested Talents') }}</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('talants.index') }}?status=2">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Approved Talents') }}</span>
                            </a>
                        </div>

                    </div>
                </div>

                @endif

                <!-- Football Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Women Football') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show womenFootballCategory'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('women-football-categories.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.women football categories') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show womenFootball'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('women-football.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.women football news') }}</span>
                            </a>
                        </div>
                        @endif
                        {{-- @endif
                        @if (auth('admin')->user()->can('show teams'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('teams.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Teams') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show teamPlayers'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('team-players.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Team players') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show team-groups'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('team_groups.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Team Group') }}</span>
                            </a>
                        </div>
                        @endif --}}
                    </div>
                </div>


                  <!-- Mmen Football Management -->
                  <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Men Football') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">

                        @if (auth('admin')->user()->can('show teams'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('teams.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Teams') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show teamPlayers'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('team-players.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Team players') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show team-groups'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('team_groups.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Team Group') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Awards -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Awards') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show award'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('awards.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Awards') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show award-redeem-requests'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('award-redeem-requests.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.Award Redeem Requests') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Settings -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">{{ helperTrans('admin.Settings') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (auth('admin')->user()->can('show settings'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('settings.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.settings') }}</span>
                            </a>
                        </div>
                        @endif
                        @if (auth('admin')->user()->can('show translation'))
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('translation.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ helperTrans('admin.translation') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end::Aside-->