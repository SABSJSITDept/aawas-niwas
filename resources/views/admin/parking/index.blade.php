@extends('admin.layout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    corePlugins: {
      preflight: false,
    }
  }
</script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="p-6 font-['Inter']">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl shadow-sm">
                <i class="bi bi-p-circle-fill"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">पार्किंग प्रबंधन</h1>
                <p class="text-sm text-slate-500 font-medium">Manage parking locations</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.parking.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="bi bi-plus-lg"></i> नया जोड़ें (Add New)
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl shadow-sm">
        <i class="bi bi-check-circle-fill text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($locations->isEmpty())
        <div class="p-12 text-center flex flex-col items-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4">
                <i class="bi bi-inbox"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700">कोई रिकॉर्ड नहीं मिला</h3>
            <p class="text-slate-500 mt-1">अभी तक कोई पार्किंग स्थान नहीं जोड़ा गया है।</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">नाम</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Icon</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Map URL</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">स्थिति</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">क्रियाएँ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($locations as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $item->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex w-10 h-10 rounded-lg items-center justify-center text-xl text-white shadow-sm" style="background: linear-gradient(135deg, {{ $item->color }} 0%, {{ $item->color_light }} 100%)">
                                <i class="bi {{ $item->icon }}"></i>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ $item->map_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                Map <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100/50 text-emerald-700 border border-emerald-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.parking.toggle', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $item->status ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}" title="{{ $item->status ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi {{ $item->status ? 'bi-pause-fill' : 'bi-play-fill' }} text-lg"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.parking.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.parking.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('क्या आप वाकई इसे हटाना चाहते हैं?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors" title="Delete">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
