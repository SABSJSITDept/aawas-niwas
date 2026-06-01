@extends('admin.layout')

@section('title', 'Dashboard Report')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  :root{
    --card-radius: 1rem;
    --card-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    --accent-1: linear-gradient(135deg,#6a11cb,#2575fc);
    --accent-2: linear-gradient(135deg,#56ab2f,#a8e063);
    --accent-3: linear-gradient(135deg,#f7b733,#fc4a1a);
    --accent-4: linear-gradient(135deg,#ff416c,#ff4b2b);
  }

  body { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background:#f6f8fb; }

  .dash-container { max-width: 1280px; margin: 0 auto; padding: 36px 16px; }

  .card.stat {
    border: none;
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    transition: transform .22s ease, box-shadow .22s ease;
    overflow: hidden;
  }
  .card.stat:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(15,23,42,.12); }

  .stat .icon {
    width:64px; height:64px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; color:white; font-size:1.45rem;
    box-shadow: 0 6px 18px rgba(16,24,40,0.08);
  }
  .stat .title { font-size: .9rem; color:#6b7280; margin:0; }
  .stat .value { font-size: 1.9rem; font-weight:700; margin:6px 0 0; color:#0f172a; }

  .icon.accent-1{ background:var(--accent-1); }
  .icon.accent-2{ background:var(--accent-2); }
  .icon.accent-3{ background:var(--accent-3); }
  .icon.accent-4{ background:var(--accent-4); }

  /* subtle small labels */
  .muted-small { color:#6b7280; font-size:.88rem; }

  /* table card */
  .card.table-card { border-radius: .9rem; box-shadow: var(--card-shadow); border: none; }

  /* loading skeleton */
  .skeleton {
    background: linear-gradient(90deg,#f0f2f6 25%, #eceef3 37%, #f0f2f6 63%);
    background-size: 400% 100%;
    animation: shine 1.2s linear infinite;
    border-radius: .4rem;
  }
  @keyframes shine { 0%{ background-position: 200% 0 } 100%{ background-position: -200% 0 } }

  @media (max-width:768px){
    .dash-container { padding: 18px 10px; }
    .stat .value { font-size: 1.5rem; }
  }

  /* chart container sizing */
  .chart-wrap { padding: 12px; }
  .chart-empty { text-align: center; padding: 24px; color: #6b7280; }
</style>

@section('content')
<div class="dash-container">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 fw-bold">📊 Dashboard Report</h1>
      <div class="muted-small">Live summary of today's check-ins, check-outs, upcoming arrivals and pending allotments</div>
    </div>

    <div class="d-flex align-items-center gap-3">
      <form id="date-form" class="d-flex" method="GET" action="{{ route('admin.daily-room-report') }}">
        <input id="filter-date" class="form-control form-control-sm" type="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
        <button class="btn btn-primary btn-sm ms-2">Apply</button>
      </form>
    </div>
  </div>

  <!-- Stats Row -->
  <div class="row g-3 mb-4" id="stats-row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card stat p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon accent-1 me-3"><i class="fa fa-arrow-up"></i></div>
          <div>
            <p class="title mb-0">Today Check-In</p>
            <div id="today_check_in" class="value"><span class="skeleton" style="display:inline-block;width:90px;height:28px;border-radius:6px;"></span></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="card stat p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon accent-2 me-3"><i class="fa fa-arrow-down"></i></div>
          <div>
            <p class="title mb-0">Today Check-Out</p>
            <div id="today_check_out" class="value"><span class="skeleton" style="display:inline-block;width:70px;height:28px;border-radius:6px;"></span></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="card stat p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon accent-3 me-3"><i class="fa fa-calendar"></i></div>
          <div>
            <p class="title mb-0">Next 7 Days Check-In</p>
            <div id="next_seven_days_check_in" class="value"><span class="skeleton" style="display:inline-block;width:80px;height:28px;border-radius:6px;"></span></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
    <div class="card stat p-3 h-100">
  <div class="d-flex align-items-center">
    <div class="icon accent-4 me-3"><i class="fa fa-exclamation-triangle"></i></div>
    <div>
      <p class="title mb-0">Un-Alloted </p>
      <div id="till_today_not_allotted" class="value">
        <span class="skeleton" style="display:inline-block;width:60px;height:28px;border-radius:6px;"></span>
      </div>
      <div id="pending_active_sub" class="muted-small" style="margin-top:6px;">&nbsp;</div>
    </div>
  </div>
</div>

    </div>
  </div>

  <!-- Secondary metrics row (rooms/capacity) -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card p-3 table-card h-100">
        <div class="d-flex align-items-center">
          <div class="me-3 icon bg-primary-gradient" style="width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;background:linear-gradient(135deg,#6a11cb,#2575fc)"><i class="fa fa-building"></i></div>
          <div>
            <p class="title mb-1">Total Rooms</p>
            <div id="totalRooms" class="value">--</div>
            <div class="muted-small">Rooms in system (if available)</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card p-3 table-card h-100">
        <div class="d-flex align-items-center">
          <div class="me-3 icon bg-success-gradient" style="width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;background:linear-gradient(135deg,#56ab2f,#a8e063)"><i class="fa fa-bed"></i></div>
          <div>
            <p class="title mb-1">Total Capacity</p>
            <div id="bookedCapacity" class="value">--</div>
            <div class="muted-small">Total Capacity All Over</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card p-3 table-card h-100">
        <div class="d-flex align-items-center">
          <div class="me-3 icon bg-warning-gradient" style="width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;background:linear-gradient(135deg,#f7b733,#fc4a1a)"><i class="fa fa-door-open"></i></div>
          <div>
            <p class="title mb-1">Available Capacity</p>
            <div id="availableCapacity" class="value">--</div>
            <div class="muted-small">Estimated available places</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Next 7 days summary table + chart -->
  <div class="card table-card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h6 class="mb-0 fw-bold">Next 7 Days — Summary</h6>
        <div class="muted-small">Total persons expected (grouped by check-in dates)</div>
      </div>
      <div class="muted-small" id="last-updated">Loading...</div>
    </div>

    <div class="table-responsive mb-3">
      <table class="table table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Period</th>
            <th class="text-end">Total Persons</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="period-label">—</td>
            <td id="period-total" class="text-end">—</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Chart wrapper -->
    <div class="chart-wrap">
      <!-- Canvas for Chart.js -->
      <canvas id="next7DaysChart" height="120" aria-label="Next 7 days chart" role="img"></canvas>

      <!-- Fallback / empty message -->
      <div id="chart-empty" class="chart-empty" style="display:none;">
        No upcoming check-ins in the next 7 days.
      </div>
    </div>
  </div>

</div>

<!-- libs -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
(function(){
  // keep your existing summary endpoint as-is
  const SUMMARY_ENDPOINT = window.location.origin + '/dashboard-report/summary';
  // additional rooms endpoint (adjust if your route differs)
  const ROOMS_ENDPOINT = window.location.origin + '/api/dashboard_report/rooms';

  // -----------------------------
  // Value-on-top plugin for Chart.js
  // -----------------------------
  const valueOnTopPlugin = {
    id: 'valueOnTop',
    afterDatasetsDraw(chart, args, opts) {
      const { ctx, chartArea } = chart;
      ctx.save();

      const fontSize = opts.fontSize || 11;
      const offset   = opts.offset   || 6;
      const color    = opts.color    || '#0f172a';
      const showZero = opts.showZero === undefined ? true : !!opts.showZero;

      ctx.font = `${fontSize}px "Segoe UI", Roboto, Arial, sans-serif`;
      ctx.fillStyle = color;
      ctx.textAlign = 'center';
      ctx.textBaseline = 'bottom';

      chart.data.datasets.forEach((dataset, datasetIndex) => {
        const meta = chart.getDatasetMeta(datasetIndex);
        if (meta.hidden) return;

        meta.data.forEach((element, index) => {
          const rawValue = dataset.data?.[index];
          if (rawValue === undefined || rawValue === null) return;
          const numeric = Number(rawValue);
          if (!showZero && numeric === 0) return;

          const pos = element.tooltipPosition ? element.tooltipPosition() : { x: element.x, y: element.y };
          const x = pos.x;
          const y = Math.max(chartArea.top + 6, pos.y - offset);

          const text = (Number.isFinite(numeric) ? Math.round(numeric) : rawValue).toString();
          ctx.fillText(text, x, y);
        });
      });

      ctx.restore();
    }
  };

  if (window.Chart && !Chart.registry.plugins.get('valueOnTop')) {
    Chart.register(valueOnTopPlugin);
  }

  // -----------------------------
  // Elements map
  // -----------------------------
  const els = {
    today_check_in: document.getElementById('today_check_in'),
    today_check_out: document.getElementById('today_check_out'),
    next_seven_days_check_in: document.getElementById('next_seven_days_check_in'),
    till_today_not_allotted: document.getElementById('till_today_not_allotted'),
    pending_active_sub: document.getElementById('pending_active_sub'),
    totalRooms: document.getElementById('totalRooms'),
    bookedCapacity: document.getElementById('bookedCapacity'),
    availableCapacity: document.getElementById('availableCapacity'),
    periodLabel: document.getElementById('period-label'),
    periodTotal: document.getElementById('period-total'),
    lastUpdated: document.getElementById('last-updated'),
    chartCanvas: document.getElementById('next7DaysChart'),
    chartEmpty: document.getElementById('chart-empty'),
  };

  // safe setter
  const set = (id, value) => {
    if(!els[id]) return;
    els[id].textContent = (value === null || value === undefined) ? '—' : value;
  };

  // pretty period label
  function prettyDateRange() {
    const now = new Date();
    const start = new Date(now); start.setDate(now.getDate() + 1); // tomorrow
    const end = new Date(now); end.setDate(now.getDate() + 7); // 7 days ahead (exclusive end)
    const opts = { weekday:'short', day:'2-digit', month:'short' , year:'numeric' };
    const endDisplay = new Date(end.getFullYear(), end.getMonth(), end.getDate()-1);
    return `${start.toLocaleDateString(undefined, opts)} — ${endDisplay.toLocaleDateString(undefined, opts)}`;
  }

  // Chart instance ref + helpers
  let chartInstance = null;
  function destroyChart() {
    if(chartInstance){
      try { chartInstance.destroy(); } catch(e) { /* ignore */ }
      chartInstance = null;
    }
  }

  function buildDatasets(days) {
    const labels = days.map(d => d.date);

    const hasBreakdown = days.some(d => d.breakdown && (d.breakdown.family !== undefined || d.breakdown.group !== undefined));

    if(hasBreakdown){
      const familyData = days.map(d => (d.breakdown && d.breakdown.family) ? Number(d.breakdown.family) : 0);
      const groupData  = days.map(d => (d.breakdown && d.breakdown.group)  ? Number(d.breakdown.group)  : 0);

      return {
        labels,
        datasets: [
          {
            label: 'Family',
            data: familyData,
            borderWidth: 1,
            backgroundColor: 'rgba(54,162,235,0.65)',
            borderColor: 'rgba(54,162,235,1)'
          },
          {
            label: 'Group',
            data: groupData,
            borderWidth: 1,
            backgroundColor: 'rgba(75,192,192,0.65)',
            borderColor: 'rgba(75,192,192,1)'
          }
        ]
      };
    }

    // fallback: single combined dataset using total_persons
    const totals = days.map(d => Number(d.total_persons ?? 0));
    return {
      labels,
      datasets: [
        {
          label: 'Total Persons',
          data: totals,
          borderWidth: 1,
          backgroundColor: 'rgba(99,102,241,0.65)',
          borderColor: 'rgba(99,102,241,1)'
        }
      ]
    };
  }

  function renderChart(labels, datasets) {
    destroyChart();

    const totalSum = datasets.reduce((acc, ds) => acc + (Array.isArray(ds.data) ? ds.data.reduce((a,b)=>a+b,0) : 0), 0);
    if(!labels.length || totalSum === 0){
      if(els.chartCanvas) els.chartCanvas.style.display = 'none';
      if(els.chartEmpty) { els.chartEmpty.style.display = 'block'; els.chartEmpty.textContent = 'No upcoming check-ins in the next 7 days.'; }
      return;
    } else {
      if(els.chartCanvas) els.chartCanvas.style.display = 'block';
      if(els.chartEmpty) els.chartEmpty.style.display = 'none';
    }

    const ctx = els.chartCanvas.getContext('2d');

    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: { padding: { top: 22 } },
        scales: {
          x: { stacked: false, ticks: { autoSkip: false } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        },
        plugins: {
          legend: { position: 'top' },
          tooltip: {
            callbacks: {
              label: function(context){
                const label = context.dataset.label || '';
                const value = context.parsed?.y !== undefined ? context.parsed.y : context.parsed;
                return label ? `${label}: ${value} persons` : `${value} persons`;
              }
            }
          },
          valueOnTop: {
            showZero: true,
            offset: 8,
            fontSize: 11,
            color: '#0f172a'
          }
        }
      }
    });
  }

  // -----------------------------
  // Main fetch routine (calls both summary and rooms endpoints)
  // -----------------------------
  async function fetchData(){
    try {
      // placeholders
      ['today_check_in','today_check_out','next_seven_days_check_in','till_today_not_allotted'].forEach(k => set(k, 'Loading...'));
      set('periodLabel', 'Loading...');
      set('periodTotal', 'Loading...');
      if(els.lastUpdated) els.lastUpdated.textContent = 'Fetching...';
      if(els.pending_active_sub) els.pending_active_sub.textContent = '\u00A0';
      set('totalRooms','Loading...');
      set('bookedCapacity','Loading...');
      set('availableCapacity','Loading...');

      // parallel fetch: keep SUMMARY endpoint unchanged as you requested
      const [summaryRes, roomsRes] = await Promise.all([
        axios.get(SUMMARY_ENDPOINT).catch(e => null),
        axios.get(ROOMS_ENDPOINT).catch(e => null)
      ]);

      const summary = summaryRes && summaryRes.data ? (summaryRes.data.data ?? summaryRes.data) : null;
      const rooms = roomsRes && roomsRes.data ? (roomsRes.data.data ?? roomsRes.data) : null;

      // === summary-driven fields ===
      if(summary){
        set('today_check_in', summary.today_check_in ?? 0);
        set('today_check_out', summary.today_check_out ?? 0);
        set('next_seven_days_check_in', summary.next_seven_days_check_in ?? 0);

        const pendingActive = (summary.pending_active_total !== undefined && summary.pending_active_total !== null)
          ? summary.pending_active_total
          : (summary.till_today_not_allotted ?? 0);

        set('till_today_not_allotted', pendingActive);

        const todayPending = (summary.till_today_not_allotted !== undefined && summary.till_today_not_allotted !== null) ? summary.till_today_not_allotted : null;
        if(els.pending_active_sub){
          if (summary.pending_active_total !== undefined && summary.pending_active_total !== null){
            if (todayPending !== null){
              els.pending_active_sub.textContent = `Today: ${todayPending} • Active: ${summary.pending_active_total}`;
            } else {
              els.pending_active_sub.textContent = `Active: ${summary.pending_active_total}`;
            }
          } else if (todayPending !== null) {
            els.pending_active_sub.textContent = `Today: ${todayPending}`;
          } else {
            els.pending_active_sub.textContent = '\u00A0';
          }
        }

        // period
        set('periodLabel', prettyDateRange());
        set('periodTotal', summary.next_seven_days_check_in ?? '—');

        // chart: expect summary.next_seven_days_datewise
        const days = Array.isArray(summary.next_seven_days_datewise) ? summary.next_seven_days_datewise : [];
        const fmtDays = days.map(d => {
          const labelDate = d.date;
          let display = labelDate;
          try {
            const dt = new Date(labelDate);
            const opts = { day:'2-digit', month:'short' };
            display = dt.toLocaleDateString(undefined, opts);
          } catch (e) { /* ignore */ }
          return {
            date: display,
            total_persons: d.total_persons ?? ( (d.breakdown && (d.breakdown.family || d.breakdown.group)) ? ((d.breakdown.family || 0) + (d.breakdown.group || 0)) : 0 ),
            breakdown: d.breakdown ?? null
          };
        });

        const built = buildDatasets(fmtDays);
        renderChart(built.labels, built.datasets);
      } else {
        // no summary — set dashes and hide chart
        ['today_check_in','today_check_out','next_seven_days_check_in','till_today_not_allotted','periodTotal','periodLabel'].forEach(k => set(k, '—'));
        destroyChart();
        if(els.chartCanvas) els.chartCanvas.style.display = 'none';
        if(els.chartEmpty) { els.chartEmpty.style.display = 'block'; els.chartEmpty.textContent = 'No arrivals timeline available.'; }
        if(els.pending_active_sub) els.pending_active_sub.textContent = '\u00A0';
      }

      // === rooms-driven fields ===
      if(rooms){
        // map rooms API fields into UI
        // totalRooms: use total_room_numbers if present
        const totalRoomsVal = rooms.total_room_numbers ?? rooms.total_rows ?? '—';
        set('totalRooms', totalRoomsVal);

        // ---------- CHANGED: show inventory_total_capacity as "Total Capacity" ----------
        // rooms.inventory_total_capacity is expected from your updated RoomReportController (inventory = room_count * per-room total_capacity).
        // fallback to rooms.total_capacity if inventory key missing.
        const inventoryTotal = (rooms.inventory_total_capacity !== undefined && rooms.inventory_total_capacity !== null)
          ? rooms.inventory_total_capacity
          : (rooms.total_capacity ?? null);

        set('bookedCapacity', inventoryTotal ?? '—');

        // ---------- CHANGED: compute available = inventory - booked_rooms_total ---------
        // Prefer server-provided available_after_all_bookings if present, else compute here using rooms.booked_total_all
        let availableVal = null;

        if (rooms.available_after_all_bookings !== undefined && rooms.available_after_all_bookings !== null) {
          availableVal = rooms.available_after_all_bookings;
        } else if (inventoryTotal !== null) {
          // booked total from rooms API (booked_rooms sum) expected as rooms.booked_total_all
          const bookedTotalFromRooms = (rooms.booked_total_all !== undefined && rooms.booked_total_all !== null) ? rooms.booked_total_all : null;
          if (bookedTotalFromRooms !== null) {
            availableVal = Math.max(0, Number(inventoryTotal) - Number(bookedTotalFromRooms));
          } else {
            // if booked total not present in rooms API, but summary has bookedCapacity (active bookings), we can try using that as fallback
            if (summary && summary.bookedCapacity !== undefined && summary.bookedCapacity !== null) {
              availableVal = Math.max(0, Number(inventoryTotal) - Number(summary.bookedCapacity));
            } else {
              // last fallback: show rooms.inventory_total_extra_capacity or dash
              availableVal = (rooms.inventory_total_extra_capacity !== undefined && rooms.inventory_total_extra_capacity !== null)
                ? rooms.inventory_total_extra_capacity
                : '—';
            }
          }
        } else {
          availableVal = '—';
        }

        set('availableCapacity', availableVal);

      } else {
        // rooms api failed -> fallback to summary values if present
        if(summary){
          if(summary.total_capacity !== undefined) set('bookedCapacity', summary.total_capacity);
          if(summary.availableCapacity !== undefined) set('availableCapacity', summary.availableCapacity);
        } else {
          set('totalRooms','—'); set('bookedCapacity','—'); set('availableCapacity','—');
        }
      }

      if(els.lastUpdated) els.lastUpdated.textContent = 'Last updated: ' + new Date().toLocaleString();

    } catch (err) {
      console.error('Failed to fetch dashboard summary', err);
      // set all to dash
      ['today_check_in','today_check_out','next_seven_days_check_in','till_today_not_allotted','periodTotal','periodLabel','totalRooms','bookedCapacity','availableCapacity'].forEach(k => set(k, '—'));
      if(els.lastUpdated) els.lastUpdated.textContent = '❌ Failed to fetch data';
      destroyChart();
      if(els.chartCanvas) els.chartCanvas.style.display = 'none';
      if(els.chartEmpty) { els.chartEmpty.style.display = 'block'; els.chartEmpty.textContent = 'Failed to load chart data.'; }
      if(els.pending_active_sub) els.pending_active_sub.textContent = '\u00A0';
    }
  }

  // initial fetch + periodic refresh
  fetchData();
  const REFRESH_MS = 60000; // refresh every 60s
  setInterval(fetchData, REFRESH_MS);

  // refresh when date form submitted (give server a short moment)
  const dateForm = document.getElementById('date-form');
  if(dateForm){
    dateForm.addEventListener('submit', function(e){
      e.preventDefault();
      // keep endpoints same; optionally pass date param to both endpoints if supported
      const dateVal = document.getElementById('filter-date').value;
      if(dateVal){
        const sUrl = new URL(SUMMARY_ENDPOINT, window.location.origin);
        const rUrl = new URL(ROOMS_ENDPOINT, window.location.origin);
        sUrl.searchParams.set('date', dateVal);
        rUrl.searchParams.set('date', dateVal);
        Promise.all([ axios.get(sUrl.toString()).catch(()=>null), axios.get(rUrl.toString()).catch(()=>null) ])
          .then(()=> setTimeout(fetchData, 250))
          .catch(()=> setTimeout(fetchData, 250));
      } else {
        setTimeout(fetchData, 250);
      }
    });
  }

})();
</script>

@endsection
