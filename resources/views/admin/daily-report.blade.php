<!-- DATE WISE KONSE TIME MEI KON CHECK IN OR CHECK OUT KR RHA HAI OR PEHLE SEI KON RUKA HAU HAI WOH DATA AA RHA HAI CHECK IN OR CHECK OUT KE ACCCORDING YEH DATA AA RHA HAI -->
    @extends('admin.layout')

    @section('content')
    <div class="container mt-4">
        <h2 class="text-center text-primary fw-bold mb-4">📋 Daily Stay Report - {{ $today }}</h2>

        <!-- 🔍 Date Filter Form -->
        <form method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">📅 Select Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') ?? date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    🔍 Show Report
                </button>
            </div>
        </form>

<!-- 📝 Report Table -->
<div class="card p-4 shadow-sm">
  <table class="table table-bordered table-striped text-center">
    <thead class="table-dark">
        <tr>
            <th>🕒 Time Slot</th>
            <th>👨‍👩‍👧‍👦 Family</th>
            <th>👥 Group</th>
            <th>🔢 Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>🌅 Before 10 AM</td>
            <td>{{ $familySlot0 }}</td>
            <td>{{ $groupSlot0 }}</td>
            <td>{{ $slot0Total }}</td>
        </tr>
        <tr>
            <td>🕙 10 AM – 4 PM</td>
            <td>{{ $familySlot1 }}</td>
            <td>{{ $groupSlot1 }}</td>
            <td>{{ $slot1Total }}</td>
        </tr>
        <tr>
            <td>🌇 4 PM – 10 PM</td>
            <td>{{ $familySlot2 }}</td>
            <td>{{ $groupSlot2 }}</td>
            <td>{{ $slot2Total }}</td>
        </tr>
        <tr>
            <td>🌙 10 PM – 12 AM</td>
            <td>{{ $familySlot3 }}</td>
            <td>{{ $groupSlot3 }}</td>
            <td>{{ $slot3Total }}</td>
        </tr>
        <tr class="table-success fw-bold">
            <td colspan="3">🔢 कुल</td>
            <td>{{ $todayTotal }}</td>
        </tr>
        <tr class="table-info fw-bold">
    <td colspan="3">🧍‍♂️ कल से रुके हुए लोग</td>
    <td>{{ $yesterdayTotal }}</td>
</tr>
<tr class="table-success fw-bold">
    <td colspan="3">🧮 कुल (आज + कल रुके)</td>
    <td>{{ $grandTotal }}</td>
</tr>

    </tbody>
</table>

</div>


            <div class="text-end mt-3">
                <a href="{{ route('daily.report.pdf', ['date' => request('date') ?? date('Y-m-d')]) }}" class="btn btn-danger">
                    ⬇️ Download PDF
                </a>
            </div>
        </div>
    </div>
    @endsection
