<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">UCUA Dashboard</h2>
                <div class="dash-sub">Unsafe Condition &amp; Unsafe Act reports &middot; updated {{ now()->format('d M Y, g:i A') }}</div>
            </div>
            <button type="button" id="dash-refresh" class="dash-refresh"
                onclick="this.disabled = true; this.classList.add('is-loading'); this.querySelector('span').textContent = 'Refreshing...'; location.reload();">
                <i class="bi bi-arrow-clockwise"></i><span>Refresh</span>
            </button>
        </div>
    </x-slot>

    @php
        $fmt = fn ($n) => number_format($n);
        $maxContrib = max(1, $stats['contributors']->max('reports') ?? 1);
    @endphp

    <link rel="stylesheet" href="{{ asset('/css/bootstrap-icons.min.css') }}">

    <style>
        #ucua-dash {
            --surface: #ffffff;
            --ink: #0b0b0b;
            --ink-2: #52514e;
            --muted: #898781;
            --grid: #e1e0d9;
            --axis: #c3c2b7;
            --border: rgba(11, 11, 11, 0.08);
            --blue: #2a78d6;
            --blue-track: #cde2fb;
            --orange: #eb6834;
            --orange-track: #fbd9cb;
            --good: #0ca30c;
            --good-track: #cdeccd;
            --warning: #fab219;
            --warning-track: #feecc4;
            --neutral: #898781;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--ink);
        }
        .dash-sub { margin-top: 4px; font-size: 13px; color: #6b7280; }
        .dash-refresh { display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0; padding: 9px 16px; border-radius: 10px; border: 1px solid #d1d5db; background: #fff; color: #374151; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: background-color 0.15s ease; }
        .dash-refresh i { display: inline-block; line-height: 1; }
        .dash-refresh:hover { background: #f3f4f6; }
        .dash-refresh:disabled { cursor: wait; opacity: 0.75; }
        .dash-refresh.is-loading i { animation: dash-spin 0.8s linear infinite; }
        @keyframes dash-spin { to { transform: rotate(360deg); } }

        #ucua-dash .alert-verify { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; background: #eef4fc; border: 1px solid #cde2fb; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 14px; }
        #ucua-dash .alert-verify a { font-weight: 600; color: #1c5cab; text-decoration: none; padding: 6px 12px; border-radius: 8px; background: #fff; border: 1px solid #cde2fb; }

        #ucua-dash .kpis { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; margin-bottom: 20px; }
        #ucua-dash .kpi { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 16px 18px; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
        #ucua-dash .kpi-label { font-size: 12.5px; font-weight: 600; color: var(--ink-2); display: flex; align-items: center; gap: 8px; }
        #ucua-dash .kpi-label .key { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
        #ucua-dash .kpi-value { font-size: 30px; font-weight: 700; line-height: 1.1; letter-spacing: -0.02em; }
        /* The share sits behind a divider so "1,131 | 79.3%" can't be misread as one number. */
        #ucua-dash .kpi-value { display: flex; align-items: center; flex-wrap: wrap; row-gap: 4px; }
        #ucua-dash .kpi-value small { font-size: 15px; font-weight: 600; color: var(--ink-2); letter-spacing: 0; line-height: 1; margin-left: 12px; padding: 5px 0 5px 12px; border-left: 1.5px solid var(--axis); }
        #ucua-dash .kpi-note { font-size: 12px; color: var(--muted); }
        #ucua-dash .kpi-plant { font-size: 16px; font-weight: 700; line-height: 1.3; }
        #ucua-dash .meter { height: 6px; border-radius: 3px; overflow: hidden; margin-top: auto; }
        #ucua-dash .meter > span { display: block; height: 100%; border-radius: 3px; }
        #ucua-dash .kpi-hero { background: #0d366b; border-color: #0d366b; color: #fff; }
        #ucua-dash .kpi-hero .kpi-label, #ucua-dash .kpi-hero .kpi-note { color: #b7d3f6; }
        #ucua-dash .kpi-hero .kpi-value { font-size: 40px; }

        #ucua-dash .charts { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        #ucua-dash .card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px; min-width: 0; }
        #ucua-dash .card.wide { grid-column: 1 / -1; }
        #ucua-dash .card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
        #ucua-dash .card-title { font-size: 15px; font-weight: 700; margin: 0; }
        #ucua-dash .card-sub { font-size: 12.5px; color: var(--muted); margin-top: 2px; }
        #ucua-dash .legend { display: flex; flex-wrap: wrap; gap: 6px 16px; font-size: 12.5px; color: var(--ink-2); }
        #ucua-dash .legend span { display: inline-flex; align-items: center; gap: 6px; }
        #ucua-dash .legend i { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }
        #ucua-dash .plot { position: relative; height: 280px; }
        #ucua-dash .plot.tall { height: 320px; }

        #ucua-dash .donut-wrap { display: grid; grid-template-columns: 220px 1fr; gap: 24px; align-items: center; }
        #ucua-dash .donut { position: relative; height: 220px; }
        #ucua-dash .donut-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; pointer-events: none; }
        #ucua-dash .donut-center b { font-size: 30px; font-weight: 700; line-height: 1; }
        #ucua-dash .donut-center span { font-size: 12px; color: var(--muted); margin-top: 4px; }
        #ucua-dash .status-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px; }
        #ucua-dash .status-list li { display: grid; grid-template-columns: 12px 1fr auto; gap: 10px; align-items: center; font-size: 13.5px; }
        #ucua-dash .status-list i { width: 12px; height: 12px; border-radius: 3px; }
        #ucua-dash .status-list b { font-variant-numeric: tabular-nums; }
        #ucua-dash .status-list small { color: var(--muted); font-size: 12px; margin-left: 6px; }

        #ucua-dash table { width: 100%; border-collapse: collapse; font-size: 13px; }
        #ucua-dash th { text-align: left; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--muted); padding: 8px 10px; border-bottom: 1px solid var(--grid); white-space: nowrap; }
        #ucua-dash td { padding: 10px; border-bottom: 1px solid #f1f0ec; vertical-align: middle; }
        #ucua-dash td.num, #ucua-dash th.num { text-align: right; font-variant-numeric: tabular-nums; }
        #ucua-dash .rank { display: inline-flex; width: 26px; height: 26px; border-radius: 50%; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; background: #f1f0ec; color: var(--ink-2); }
        #ucua-dash .rank.top { background: #0d366b; color: #fff; }
        #ucua-dash .who b { display: block; font-weight: 600; }
        #ucua-dash .who span { font-size: 12px; color: var(--muted); }
        #ucua-dash .bar-cell { min-width: 140px; }
        #ucua-dash .inline-bar { display: flex; align-items: center; gap: 10px; }
        #ucua-dash .inline-bar .track { flex: 1; height: 8px; background: var(--blue-track); border-radius: 4px; overflow: hidden; }
        #ucua-dash .inline-bar .fill { height: 100%; background: var(--blue); border-radius: 4px; }
        #ucua-dash .inline-bar b { min-width: 28px; text-align: right; font-variant-numeric: tabular-nums; }
        #ucua-dash .scroll-x { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        #ucua-dash .who .dept-inline { display: none; }

        #ucua-dash details { margin-top: 12px; font-size: 12.5px; }
        #ucua-dash summary { cursor: pointer; color: var(--ink-2); font-weight: 600; padding: 4px 0; }
        #ucua-dash details table { margin-top: 8px; }
        #ucua-dash details td { padding: 6px 10px; }

        @media (max-width: 1280px) {
            #ucua-dash .kpis { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 900px) {
            #ucua-dash .charts { grid-template-columns: 1fr; }
            #ucua-dash .donut-wrap { grid-template-columns: 1fr; justify-items: center; }
            #ucua-dash .status-list { width: 100%; }
        }
        @media (max-width: 640px) {
            #ucua-dash .kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
            #ucua-dash .kpi { padding: 14px; }
            #ucua-dash .kpi-value { font-size: 24px; }
            #ucua-dash .kpi-hero { grid-column: 1 / -1; }
            #ucua-dash .kpi-hero .kpi-value { font-size: 34px; }
            #ucua-dash .card { padding: 16px; }
            #ucua-dash .plot { height: 240px; }
            #ucua-dash .plot.tall { height: 300px; }
            #ucua-dash .plot-stop { height: 320px; }
            #ucua-dash .col-dept { display: none; }
            #ucua-dash .who .dept-inline { display: block; margin-top: 2px; font-size: 11.5px; line-height: 1.35; }
            #ucua-dash .bar-cell { min-width: 110px; }
            #ucua-dash td, #ucua-dash th { padding-left: 6px; padding-right: 6px; }
        }
    </style>

    <div class="py-8">
        <div id="ucua-dash" class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($numTicketsPendingVerify > 0)
                <div class="alert-verify">
                    <span>You have <b>{{ $fmt($numTicketsPendingVerify) }}</b> {{ Str::plural('observation', $numTicketsPendingVerify) }} to verify.</span>
                    <a href="{{ route('ShowListTickets', ['mode' => 1, 'category' => 1]) }}">View &rarr;</a>
                </div>
            @endif

            {{-- 1-6: headline figures --}}
            <div class="kpis">
                <div class="kpi kpi-hero">
                    <div class="kpi-label">Total UCUA reports</div>
                    <div class="kpi-value">{{ $fmt($stats['total']) }}</div>
                    <div class="kpi-note">To date</div>
                </div>

                <div class="kpi">
                    <div class="kpi-label"><i class="key" style="background: var(--blue)"></i>Unsafe conditions</div>
                    <div class="kpi-value">{{ $fmt($stats['unsafe_condition']['n']) }}<small>{{ $stats['unsafe_condition']['pct'] }}%</small></div>
                    <div class="kpi-note">of all reports</div>
                    <div class="meter" style="background: var(--blue-track)"><span style="width: {{ $stats['unsafe_condition']['pct'] }}%; background: var(--blue)"></span></div>
                </div>

                <div class="kpi">
                    <div class="kpi-label"><i class="key" style="background: var(--orange)"></i>Unsafe acts</div>
                    <div class="kpi-value">{{ $fmt($stats['unsafe_act']['n']) }}<small>{{ $stats['unsafe_act']['pct'] }}%</small></div>
                    <div class="kpi-note">of all reports</div>
                    <div class="meter" style="background: var(--orange-track)"><span style="width: {{ $stats['unsafe_act']['pct'] }}%; background: var(--orange)"></span></div>
                </div>

                <div class="kpi">
                    <div class="kpi-label"><i class="key" style="background: var(--good)"></i>Closure rate</div>
                    <div class="kpi-value">{{ $stats['closed']['pct'] }}%</div>
                    <div class="kpi-note">{{ $fmt($stats['closed']['n']) }} of {{ $fmt($stats['total']) }} closed</div>
                    <div class="meter" style="background: var(--good-track)"><span style="width: {{ $stats['closed']['pct'] }}%; background: var(--good)"></span></div>
                </div>

                <div class="kpi">
                    <div class="kpi-label"><i class="key" style="background: var(--warning)"></i>Open cases</div>
                    <div class="kpi-value">{{ $fmt($stats['open']['n']) }}<small>{{ $stats['open']['pct'] }}%</small></div>
                    <div class="kpi-note">of all reports still open</div>
                    <div class="meter" style="background: var(--warning-track)"><span style="width: {{ $stats['open']['pct'] }}%; background: var(--warning)"></span></div>
                </div>

                <div class="kpi">
                    <div class="kpi-label"><i class="bi bi-trophy"></i>Top contributing plant</div>
                    <div class="kpi-plant">{{ $stats['top_plants']->isEmpty() ? '-' : $stats['top_plants']->implode(' & ') }}</div>
                    <div class="kpi-note">
                        {{ $fmt($stats['top_plant_count']) }} {{ Str::plural('report', $stats['top_plant_count']) }}{{ $stats['top_plants']->count() > 1 ? ' each (tied)' : '' }}
                    </div>
                </div>
            </div>

            <div class="charts">
                {{-- 7: monthly reports --}}
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">Monthly UCUA reports</h3>
                            <div class="card-sub">Reports submitted each month, {{ $stats['year'] }}</div>
                        </div>
                    </div>
                    <div class="plot"><canvas id="chart-monthly" role="img" aria-label="Line chart of UCUA reports per month"></canvas></div>
                    <details>
                        <summary>View data</summary>
                        <div class="scroll-x">
                            <table>
                                <thead><tr><th>Month</th><th class="num">Reports</th></tr></thead>
                                <tbody>
                                    @foreach ($stats['months'] as $m)
                                        <tr><td>{{ $m['label'] }} {{ $stats['year'] }}</td><td class="num">{{ $fmt($m['total']) }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>

                {{-- 9: unsafe condition vs unsafe act --}}
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">Unsafe condition vs unsafe act</h3>
                            <div class="card-sub">Monthly split, {{ $stats['year'] }}</div>
                        </div>
                        <div class="legend">
                            <span><i style="background: var(--blue)"></i>Unsafe condition {{ $stats['unsafe_condition']['pct'] }}%</span>
                            <span><i style="background: var(--orange)"></i>Unsafe act {{ $stats['unsafe_act']['pct'] }}%</span>
                        </div>
                    </div>
                    <div class="plot"><canvas id="chart-type" role="img" aria-label="Stacked column chart of unsafe conditions and unsafe acts per month"></canvas></div>
                    <details>
                        <summary>View data</summary>
                        <div class="scroll-x">
                            <table>
                                <thead><tr><th>Month</th><th class="num">Unsafe condition</th><th class="num">Unsafe act</th></tr></thead>
                                <tbody>
                                    @foreach ($stats['months'] as $m)
                                        <tr><td>{{ $m['label'] }}</td><td class="num">{{ $fmt($m['condition']) }}</td><td class="num">{{ $fmt($m['act']) }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>

                {{-- 8: by plant YTD --}}
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">UCUA reports by plant &middot; YTD {{ $stats['year'] }}</h3>
                            <div class="card-sub">By plant involved, 1 Jan {{ $stats['year'] }} to today</div>
                        </div>
                    </div>
                    <div class="plot tall"><canvas id="chart-plant" role="img" aria-label="Bar chart of UCUA reports by plant, year to date"></canvas></div>
                    <details>
                        <summary>View data</summary>
                        <div class="scroll-x">
                            <table>
                                <thead><tr><th>Plant</th><th class="num">Reports</th></tr></thead>
                                <tbody>
                                    @foreach ($stats['plants_ytd'] as $p)
                                        <tr><td>{{ $p->name }}</td><td class="num">{{ $fmt($p->n) }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>

                {{-- 10: closure rate --}}
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">UCUA closure rate</h3>
                            <div class="card-sub">Status of all reports to date</div>
                        </div>
                    </div>
                    <div class="donut-wrap">
                        <div class="donut" style="width: 220px;">
                            <canvas id="chart-closure" role="img" aria-label="Donut chart of report status"></canvas>
                            <div class="donut-center"><b>{{ $stats['closed']['pct'] }}%</b><span>closed</span></div>
                        </div>
                        <ul class="status-list">
                            <li><i style="background: var(--good)"></i><span><i class="bi bi-check-circle"></i> Closed</span><span><b>{{ $fmt($stats['closed']['n']) }}</b><small>{{ $stats['closed']['pct'] }}%</small></span></li>
                            <li><i style="background: var(--warning)"></i><span><i class="bi bi-hourglass-split"></i> Open</span><span><b>{{ $fmt($stats['open']['n']) }}</b><small>{{ $stats['open']['pct'] }}%</small></span></li>
                            <li><i style="background: var(--neutral)"></i><span><i class="bi bi-x-circle"></i> Declined</span><span><b>{{ $fmt($stats['declined']['n']) }}</b><small>{{ $stats['declined']['pct'] }}%</small></span></li>
                            <li style="border-top: 1px solid var(--grid); padding-top: 10px;"><span></span><span>Total reports</span><span><b>{{ $fmt($stats['total']) }}</b></span></li>
                        </ul>
                    </div>
                </div>

                {{-- 11: STOP ranking --}}
                <div class="card wide">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">UCUA ranking by STOP category</h3>
                            <div class="card-sub">All reports to date, highest first &middot; hover a column for the category description</div>
                        </div>
                    </div>
                    <div class="plot plot-stop"><canvas id="chart-stop" role="img" aria-label="Column chart ranking STOP categories by number of reports"></canvas></div>
                    <details>
                        <summary>View data</summary>
                        <div class="scroll-x">
                            <table>
                                <thead><tr><th>Rank</th><th>Category</th><th>Description</th><th class="num">Reports</th></tr></thead>
                                <tbody>
                                    @foreach ($stats['stop'] as $i => $s)
                                        <tr><td>{{ $i + 1 }}</td><td>{{ $s->name }}</td><td>{{ $s->description }}</td><td class="num">{{ $fmt($s->n) }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>

                {{-- 12: top contributors --}}
                <div class="card wide">
                    <div class="card-head">
                        <div>
                            <h3 class="card-title">Top 10 UCUA contributors</h3>
                            <div class="card-sub">Staff with the most reports to date</div>
                        </div>
                    </div>
                    <div class="scroll-x">
                        <table>
                            <thead>
                                <tr><th>#</th><th>Staff</th><th class="col-dept">Department</th><th>Reports</th><th class="num">Closed</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($stats['contributors'] as $i => $c)
                                    <tr>
                                        <td><span class="rank {{ $i < 3 ? 'top' : '' }}">{{ $i + 1 }}</span></td>
                                        <td class="who"><b>{{ Str::title(Str::lower($c['name'])) }}</b><span>{{ $c['staff_id'] }}</span><span class="dept-inline">{{ $c['department'] }}</span></td>
                                        <td class="col-dept" style="font-size: 12.5px; color: var(--ink-2); min-width: 180px;">{{ $c['department'] }}</td>
                                        <td class="bar-cell">
                                            <div class="inline-bar">
                                                <div class="track"><div class="fill" style="width: {{ round($c['reports'] * 100 / $maxContrib) }}%"></div></div>
                                                <b>{{ $fmt($c['reports']) }}</b>
                                            </div>
                                        </td>
                                        <td class="num">{{ $fmt($c['closed']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="color: var(--muted);">No reports yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/vendor/chart.umd.min.js') }}"></script>
    <script>
        (function () {
            var css = getComputedStyle(document.getElementById('ucua-dash'));
            var c = function (name) { return css.getPropertyValue(name).trim(); };
            var stats = @json($stats);
            var nf = new Intl.NumberFormat();

            Chart.defaults.font.family = 'system-ui, -apple-system, "Segoe UI", sans-serif';
            Chart.defaults.font.size = 12;
            Chart.defaults.color = c('--muted');
            Chart.defaults.plugins.legend.display = false;
            Chart.defaults.maintainAspectRatio = false;
            Object.assign(Chart.defaults.plugins.tooltip, {
                backgroundColor: '#0b0b0b', padding: 10, cornerRadius: 8, boxPadding: 4,
                titleFont: { weight: '600' }, usePointStyle: true,
            });

            var gridAxis = { grid: { color: c('--grid'), drawTicks: false }, border: { display: false }, ticks: { padding: 8, precision: 0 } };
            var catAxis = { grid: { display: false }, border: { color: c('--axis') }, ticks: { padding: 6 } };

            // Draws each bar's value just past its tip (bars/columns only; skipped when there's no room).
            var tipLabels = {
                id: 'tipLabels',
                afterDatasetsDraw: function (chart, args, opts) {
                    if (!opts || !opts.enabled) return;
                    var ctx = chart.ctx, horizontal = chart.options.indexAxis === 'y';
                    var meta = chart.getDatasetMeta(chart.data.datasets.length - 1);
                    ctx.save();
                    ctx.font = '600 12px system-ui, -apple-system, "Segoe UI", sans-serif';
                    ctx.fillStyle = c('--ink-2');
                    meta.data.forEach(function (bar, i) {
                        var total = chart.data.datasets.reduce(function (s, d) { return s + (d.data[i] || 0); }, 0);
                        if (!total) return;
                        var text = nf.format(total);
                        if (horizontal) {
                            ctx.textAlign = 'left'; ctx.textBaseline = 'middle';
                            if (bar.x + 6 + ctx.measureText(text).width <= chart.chartArea.right + 40) ctx.fillText(text, bar.x + 6, bar.y);
                        } else {
                            ctx.textAlign = 'center'; ctx.textBaseline = 'bottom';
                            ctx.fillText(text, bar.x, bar.y - 4);
                        }
                    });
                    ctx.restore();
                }
            };

            // 7. Monthly reports - single-series area line
            new Chart(document.getElementById('chart-monthly'), {
                type: 'line',
                data: {
                    labels: stats.months.map(function (m) { return m.label; }),
                    datasets: [{
                        label: 'Reports', data: stats.months.map(function (m) { return m.total; }),
                        borderColor: c('--blue'), backgroundColor: 'rgba(42, 120, 214, 0.10)', fill: true,
                        borderWidth: 2, cubicInterpolationMode: 'monotone', borderCapStyle: 'round', borderJoinStyle: 'round',
                        pointRadius: 4, pointHoverRadius: 6, pointBackgroundColor: c('--blue'),
                        pointBorderColor: c('--surface'), pointBorderWidth: 2, pointHitRadius: 14,
                    }]
                },
                options: {
                    interaction: { mode: 'index', intersect: false },
                    layout: { padding: { top: 8 } },
                    scales: { y: Object.assign({ beginAtZero: true }, gridAxis), x: catAxis },
                    plugins: { tooltip: { callbacks: { label: function (i) { return ' ' + nf.format(i.raw) + ' reports'; } } } },
                }
            });

            // 9. Unsafe condition vs act - stacked columns per month
            new Chart(document.getElementById('chart-type'), {
                type: 'bar',
                data: {
                    labels: stats.months.map(function (m) { return m.label; }),
                    datasets: [
                        { label: 'Unsafe condition', data: stats.months.map(function (m) { return m.condition; }), backgroundColor: c('--blue'),
                          borderColor: c('--surface'), borderWidth: { top: 2 }, borderSkipped: 'start', maxBarThickness: 24 },
                        { label: 'Unsafe act', data: stats.months.map(function (m) { return m.act; }), backgroundColor: c('--orange'),
                          borderRadius: { topLeft: 4, topRight: 4 }, borderSkipped: 'start', maxBarThickness: 24 },
                    ]
                },
                options: {
                    interaction: { mode: 'index', intersect: false },
                    layout: { padding: { top: 20 } },
                    scales: { x: Object.assign({ stacked: true }, catAxis), y: Object.assign({ stacked: true, beginAtZero: true }, gridAxis) },
                    plugins: {
                        tipLabels: { enabled: true },
                        tooltip: { callbacks: {
                            label: function (i) {
                                var total = stats.months[i.dataIndex].total;
                                var share = total ? Math.round(i.raw * 1000 / total) / 10 : 0;
                                return ' ' + i.dataset.label + ': ' + nf.format(i.raw) + ' (' + share + '%)';
                            },
                            footer: function (items) { return 'Total: ' + nf.format(stats.months[items[0].dataIndex].total); },
                        } },
                    },
                },
                plugins: [tipLabels]
            });

            // 8. Reports by plant YTD - horizontal bars, highest first
            new Chart(document.getElementById('chart-plant'), {
                type: 'bar',
                data: {
                    labels: stats.plants_ytd.map(function (p) { return p.name; }),
                    datasets: [{
                        label: 'Reports', data: stats.plants_ytd.map(function (p) { return p.n; }),
                        backgroundColor: stats.plants_ytd.map(function (p) { return p.name === 'Not recorded' ? c('--neutral') : c('--blue'); }),
                        borderRadius: { topRight: 4, bottomRight: 4 }, borderSkipped: 'start', maxBarThickness: 24,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    layout: { padding: { right: 44 } },
                    scales: { x: Object.assign({ beginAtZero: true }, gridAxis), y: Object.assign({}, catAxis, { ticks: { color: c('--ink-2'), padding: 6 } }) },
                    plugins: {
                        tipLabels: { enabled: true },
                        tooltip: { callbacks: { label: function (i) { return ' ' + nf.format(i.raw) + ' reports'; } } },
                    },
                },
                plugins: [tipLabels]
            });

            // 10. Closure rate - status donut
            new Chart(document.getElementById('chart-closure'), {
                type: 'doughnut',
                data: {
                    labels: ['Closed', 'Open', 'Declined'],
                    datasets: [{
                        data: [stats.closed.n, stats.open.n, stats.declined.n],
                        backgroundColor: [c('--good'), c('--warning'), c('--neutral')],
                        borderColor: c('--surface'), borderWidth: 2, hoverOffset: 4,
                    }]
                },
                options: {
                    cutout: '72%',
                    plugins: { tooltip: { callbacks: {
                        label: function (i) {
                            var pct = stats.total ? Math.round(i.raw * 1000 / stats.total) / 10 : 0;
                            return ' ' + i.label + ': ' + nf.format(i.raw) + ' (' + pct + '%)';
                        }
                    } } },
                }
            });

            // 11. STOP ranking - columns, highest first; the leader is emphasised, the rest recede.
            // Eight column labels don't fit across a phone, so narrow screens get horizontal bars.
            var narrow = window.matchMedia('(max-width: 640px)').matches;
            var stopCat = Object.assign({}, catAxis, { ticks: { color: c('--ink-2'), autoSkip: false, maxRotation: 0, font: { size: 11 } } });
            new Chart(document.getElementById('chart-stop'), {
                type: 'bar',
                data: {
                    labels: stats.stop.map(function (s, i) { return narrow ? '#' + (i + 1) + ' ' + s.name : ['#' + (i + 1), s.name]; }),
                    datasets: [{
                        label: 'Reports', data: stats.stop.map(function (s) { return s.n; }),
                        backgroundColor: stats.stop.map(function (s, i) { return i === 0 ? c('--blue') : '#86b6ef'; }),
                        borderRadius: narrow ? { topRight: 4, bottomRight: 4 } : { topLeft: 4, topRight: 4 },
                        borderSkipped: 'start', maxBarThickness: 24,
                    }]
                },
                options: {
                    indexAxis: narrow ? 'y' : 'x',
                    layout: { padding: narrow ? { right: 44 } : { top: 20 } },
                    scales: narrow
                        ? { x: Object.assign({ beginAtZero: true }, gridAxis), y: stopCat }
                        : { y: Object.assign({ beginAtZero: true }, gridAxis), x: stopCat },
                    plugins: {
                        tipLabels: { enabled: true },
                        tooltip: { callbacks: {
                            title: function (items) { return stats.stop[items[0].dataIndex].name; },
                            label: function (i) {
                                var pct = stats.total ? Math.round(i.raw * 1000 / stats.total) / 10 : 0;
                                return ' ' + nf.format(i.raw) + ' reports (' + pct + '%)';
                            },
                            afterLabel: function (i) { return stats.stop[i.dataIndex].description || ''; },
                        } },
                    },
                },
                plugins: [tipLabels]
            });
        })();
    </script>
</x-app-layout>
