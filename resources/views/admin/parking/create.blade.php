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

<div class="p-6 font-['Inter'] max-w-4xl mx-auto">
    
    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.parking.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 flex items-center justify-center transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ isset($parking) ? 'पार्किंग संपादित करें' : 'नई पार्किंग जोड़ें' }}</h1>
            <p class="text-sm text-slate-500 font-medium">Fill in the details below</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <form method="POST" action="{{ isset($parking) ? route('admin.parking.update', $parking->id) : route('admin.parking.store') }}">
            @csrf
            @if(isset($parking))
                @method('PUT')
            @endif

            <div class="p-8 space-y-6">
                <!-- Errors -->
                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">नाम (Name) <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" value="{{ old('name', $parking->name ?? '') }}" placeholder="जैसे: पार्किंग स्थान 1" required>
                    </div>

                    <!-- Map URL -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Google Map URL</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-link-45deg text-lg"></i>
                            </div>
                            <input type="text" name="map_url" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" value="{{ old('map_url', $parking->map_url ?? '') }}" placeholder="https://maps.google.com/...">
                        </div>
                    </div>


                </div>

                <div class="mt-8 flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <input type="hidden" name="status" value="0">
                    <input class="w-5 h-5 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="status" value="1" id="statusCheck" {{ old('status', $parking->status ?? 1) ? 'checked' : '' }}>
                    <label class="text-sm font-bold text-slate-700 cursor-pointer" for="statusCheck">
                        सक्रिय रखें (Active)
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-slate-50/80 p-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.parking.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                    रद्द करें (Cancel)
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow rounded-xl transition-all flex items-center gap-2">
                    <i class="bi bi-save2"></i> {{ isset($parking) ? 'अपडेट करें (Update)' : 'सेव करें (Save)' }}
                </button>
            </div>
            
        </form>
    </div>

</div>
@endsection
