@extends('Admin.layouts.inc.app')
@section('title')
    {{helperTrans('admin.Home')}}
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .recent-activity {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('toolbar')
    <div class="d-flex align-items-center">
        <h1 class="text-dark fw-bolder my-1 fs-3">{{helperTrans('admin.Dashboard')}}</h1>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row g-5 g-xl-8 mb-5">
        <!-- Users Card -->
        <div class="col-xl-3">
            <a href="{{ route('users.index') }}">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-primary">
                                <i class="mdi mdi-account-group text-primary fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalUsers ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Users')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        </div>

        <!-- Teams Card -->
        <div class="col-xl-3">
            <a href="{{ route('teams.index') }}">
            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-success">
                                <i class="mdi mdi-account-multiple text-success fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalTeams ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Teams')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        </div>

        <!-- Talents Card -->
        <div class="col-xl-3">
            <a href="{{ route('talants.index') }}?status=1">
            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-info">
                                <i class="mdi mdi-account-box text-info fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalTalents ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Requested Talents')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        </div>

        {{-- <!-- Players Card -->
        <div class="col-xl-3">
            <a href="{{ route('team-players.index') }}">
            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-info">
                                <i class="mdi mdi-account-box text-info fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalPlayers ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Players')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        </div> --}}


        <!-- Products Card -->
        <div class="col-xl-3">
            <a href="{{ route('products.index') }}">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-warning">
                                <i class="mdi mdi-shopping text-warning fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalProducts ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Product Requests')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>

        {{-- <!-- Awards Card -->
        <div class="col-xl-3">
            <a href="{{ route('awards.index') }}">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-danger">
                                <i class="mdi mdi-trophy text-danger fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalAwards ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Total Awards')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div> --}}

        <!-- Redeem Requests Card -->
        <div class="col-xl-3">
            <a href="{{ route('award-redeem-requests.index') }}">

            <div class="card card-xl-stretch mb-xl-8 stat-card">
                <div class="card-body p-0">
                    <div class="d-flex flex-stack card-p flex-grow-1">
                        <span class="symbol symbol-50px me-2">
                            <span class="symbol-label bg-light-info">
                                <i class="mdi mdi-gift text-info fs-2x"></i>
                            </span>
                        </span>
                        <div class="d-flex flex-column text-end">
                            <span class="text-dark fw-bolder fs-2">{{$totalRedeemRequests ?? 0}}</span>
                            <span class="text-muted fw-bold mt-1">{{helperTrans('admin.Redeem Requests')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-5 g-xl-8 mb-5">
        <!-- User Growth Chart -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.User Growth')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Talent Distribution Chart -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Talent Distribution')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="talentDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Distribution Chart -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Players Distribution')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="playersDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Teams Distribution Chart -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Most Popular Teams')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="favoriteTeamsDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Award Distribution Chart -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Award Distribution')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="awardDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Top Teams -->
    <div class="row g-5 g-xl-8">
        <!-- Recent Activity -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Recent Activity')}}</span>
                    </h3>
                </div>
                <div class="card-body recent-activity">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-light-{{$activity->type}}">
                                        <i class="mdi mdi-{{$activity->icon}} text-{{$activity->type}} fs-2x"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-bolder fs-6">{{$activity->title}}</span>
                                    <span class="text-muted fw-bold">{{$activity->description}}</span>
                                    <span class="text-muted fw-bold mt-1">{{$activity->created_at->diffForHumans()}}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            {{helperTrans('admin.No recent activities')}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Players -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">{{helperTrans('admin.Recent Players')}}</span>
                    </h3>
                </div>
                <div class="card-body">
                    @if(isset($recentPlayers) && count($recentPlayers) > 0)
                        @foreach($recentPlayers as $player)
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-50px me-5">
                                    @if($player->image)
                                        <img src="{{$player->image}}" alt="{{$player->title}}">
                                    @else
                                        <span class="symbol-label bg-light-info">
                                            <i class="mdi mdi-account-box text-info fs-2x"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-bolder fs-6">{{$player->title}}</span>
                                    <span class="text-muted fw-bold">{{$player->team->title ?? 'No Team'}}</span>
                                    @if($player->number)
                                        <span class="text-muted fw-bold mt-1">#{{$player->number}}</span>
                                    @endif
                                </div>
                                <div class="ms-auto">
                                    <span class="text-muted fw-bold">{{$player->created_at->diffForHumans()}}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            {{helperTrans('admin.No players available')}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User Growth Chart
            const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($userGrowthLabels ?? []) !!},
                    datasets: [{
                        label: '{{helperTrans("admin.Users")}}',
                        data: {!! json_encode($userGrowthData ?? []) !!},
                        borderColor: '#3699FF',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Talent Distribution Chart
            const talentDistributionCtx = document.getElementById('talentDistributionChart').getContext('2d');
            new Chart(talentDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($talentDistributionLabels ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($talentDistributionData ?? []) !!},
                        backgroundColor: [
                            '#FFA800', // Pending - Orange
                            '#1BC5BD', // Approved - Green
                            '#F64E60', // Rejected - Red
                            '#8950FC'  // Hidden - Purple
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Players Distribution Chart
            const playersDistributionCtx = document.getElementById('playersDistributionChart').getContext('2d');
            new Chart(playersDistributionCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($playersDistributionLabels ?? []) !!},
                    datasets: [{
                        label: '{{helperTrans("admin.Players")}}',
                        data: {!! json_encode($playersDistributionData ?? []) !!},
                        backgroundColor: '#3699FF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Favorite Teams Distribution Chart
            const favoriteTeamsDistributionCtx = document.getElementById('favoriteTeamsDistributionChart').getContext('2d');
            new Chart(favoriteTeamsDistributionCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($favoriteTeamsDistributionLabels ?? []) !!},
                    datasets: [{
                        label: '{{helperTrans("admin.Favorite Teams")}}',
                        data: {!! json_encode($favoriteTeamsDistributionData ?? []) !!},
                        backgroundColor: '#1BC5BD'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Award Distribution Chart
            const awardDistributionCtx = document.getElementById('awardDistributionChart').getContext('2d');
            new Chart(awardDistributionCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($awardDistributionLabels ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($awardDistributionData ?? []) !!},
                        backgroundColor: [
                            '#FFA800', // Pending - Orange
                            '#1BC5BD', // Approved - Green
                            '#F64E60'  // Rejected - Red
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
