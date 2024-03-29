<div class="row">
    <div class="col-md-6">
        <div class="card card-chart">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    {{ trans('messages.total_cases') }}
                </h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <button class="nav-link btn-sm mr-1 active" data-chart="total_cases" data-axis-scale="linear">{{ trans('messages.linear') }}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-sm" data-chart="total_cases" data-axis-scale="logarithmic">{{ trans('messages.logarithmic') }}</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <canvas id="total_cases"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-chart">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    {{ trans('messages.total_deaths') }}
                </h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <button class="nav-link btn-sm mr-1 active" data-chart="total_deaths" data-axis-scale="linear">{{ trans('messages.linear') }}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-sm" data-chart="total_deaths" data-axis-scale="logarithmic">{{ trans('messages.logarithmic') }}</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <canvas id="total_deaths"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-chart">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    {{ trans('messages.new_cases') }}
                </h3>
            </div>
            <div class="card-body">
                <canvas id="new_cases"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-chart">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    {{ trans('messages.new_deaths') }}
                </h3>
            </div>
            <div class="card-body">
                <canvas id="new_deaths"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card card-chart">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">
            @lang('messages.total_tests') / @lang('messages.total_cases')
        </h3>
    </div>
    <div class="card-body">
        <canvas id="total_tests_vs_total_cases"></canvas>
    </div>
</div>

<div class="card card-chart">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">
             @lang('messages.total_cases') / @lang('messages.total_hospitalized') / @lang('messages.total_critical')
        </h3>
    </div>
    <div class="card-body">
        <canvas id="total_cases_vs_total_hospitalized_vs_total_critical"></canvas>
    </div>
</div>

<div class="card card-chart">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">
            @lang('messages.new_tests') / @lang('messages.new_cases')
        </h3>
    </div>
    <div class="card-body">
        <canvas id="new_tests_vs_new_cases"></canvas>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        $(document).ready(function () {
            var charts = {};
            var chartConfig = {
                linear: [{
                    type: 'linear',
                }],
                logarithmic: [{
                    type: 'logarithmic',
                    ticks: {
                        min: 0,
                        max: 10000,
                        stepSize: 100,
                        callback: function (value) {
                            return Number(value.toString());
                        }
                    },
                    afterBuildTicks: function (chart) {
                        chart.ticks = [0.1, 1, 10, 100, 1000, 10000];
                    }
                }]
            };
            var defaultOptions = {
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: chartConfig.linear
                }
            };
            charts.total_cases = new Chart('total_cases', {
                type: 'line',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.total_cases')',
                            data: @json($reports->pluck('total_cases')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(255, 193, 5, 0.3)',
                            borderColor: 'rgb(255, 193, 5)',
                        },
                    ]
                },
                options: defaultOptions
            });

            new Chart('new_cases', {
                type: 'bar',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.new_cases')',
                            data: @json($reports->pluck('new_cases')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(255, 193, 5, 0.3)',
                            borderColor: 'rgb(255, 193, 5)',
                        },
                    ]
                },
                options: defaultOptions
            });

            charts.total_deaths = new Chart('total_deaths', {
                type: 'line',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.total_deaths')',
                            data: @json($reports->pluck('total_deaths')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(52, 58, 64, 0.3)',
                            borderColor: 'rgb(52, 58, 64)',
                        },
                    ]
                },
                options: defaultOptions
            });

            new Chart('new_deaths', {
                type: 'bar',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.new_deaths')',
                            data: @json($reports->pluck('new_deaths')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(52, 58, 64, 0.3)',
                            borderColor: 'rgb(52, 58, 64)',
                        },
                    ]
                },
                options: defaultOptions
            });

            new Chart('total_tests_vs_total_cases', {
                type: 'line',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.total_tests')',
                            data: @json($reports->pluck('total_tests')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(8,130,220, 0.3)',
                            borderColor: 'rgb(8,130,220)',
                        },
                        {
                            label: '@lang('messages.total_cases')',
                            data: @json($reports->pluck('total_cases')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(255, 193, 5, 0.3)',
                            borderColor: 'rgb(255, 193, 5)',
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: chartConfig.linear
                    }
                }
            });

            new Chart('total_cases_vs_total_hospitalized_vs_total_critical', {
                type: 'line',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.total_cases')',
                            data: @json($reports->pluck('total_cases')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(255, 193, 5, 0.3)',
                            borderColor: 'rgb(255, 193, 5)',
                        },
                        {
                            label: '@lang('messages.total_hospitalized')',
                            data: @json($reports->pluck('total_hospitalized')->toArray()),
                            borderWidth: 1,
                            backgroundColor : 'rgba(255,96,0, 0.3)',
                            borderColor: 'rgb(255,96,0)',
                        },
                        {
                            label: '@lang('messages.total_critical')',
                            data: @json($reports->pluck('total_critical')->toArray()),
                            borderWidth: 1,
                            backgroundColor: 'rgba(255,42,0, 0.3)',
                            borderColor: 'rgb(255,42,0)',
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: chartConfig.linear
                    }
                }
            });

            new Chart('new_tests_vs_new_cases', {
                type: 'bar',
                data: {
                    labels: @json($dateLabels),
                    datasets: [
                        {
                            label: '@lang('messages.new_tests')',
                            data: @json($reports->pluck('new_tests')->toArray()),
                            borderWidth: 1,
                            yAxisID: 'A',
                            backgroundColor: 'rgba(8,130,220, 0.3)',
                            borderColor: 'rgb(8,130,220)',
                        },
                        {
                            label: '@lang('messages.new_cases')',
                            data: @json($reports->pluck('new_cases')->toArray()),
                            borderWidth: 1,
                            yAxisID: 'B',
                            backgroundColor: 'rgba(255, 193, 5, 0.3)',
                            borderColor: 'rgb(255, 193, 5)',
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            id: 'A',
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                max: 1800,
                                min: 0
                            }
                        }, {
                            id: 'B',
                            type: 'linear',
                            position: 'right',
                            ticks: {
                                max: 180,
                                min: 0
                            }
                        }]
                    }
                }
            });

            $('canvas').closest('.card').find('[data-axis-scale]').on('click', function () {
                var axisScale = $(this).data('axis-scale');
                var chart = $(this).data('chart');
                charts[chart].options.scales.yAxes = chartConfig[axisScale];
                charts[chart].update();

                $(this).closest('ul').find('button').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endpush
