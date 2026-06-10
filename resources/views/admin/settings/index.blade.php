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

<div class="p-6 font-['Inter'] max-w-5xl mx-auto">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl shadow-sm">
                <i class="bi bi-gear-fill"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">सामान्य सेटिंग्स</h1>
                <p class="text-sm text-slate-500 font-medium">Manage Site Configuration</p>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl shadow-sm">
        <i class="bi bi-check-circle-fill text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.store') }}">
        @csrf
        
        <div class="space-y-8">
            
            <!-- Location Settings Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-emerald-50/50 p-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Location Page Settings</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Google Map Iframe URL (src)</label>
                        <input type="text" name="location_map_iframe" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-700" value="{{ $settings['location_map_iframe'] ?? 'https://www.google.com/maps?q=Seva+Sadan+Chabali+Ghati+Bikaner&hl=en&z=17&output=embed' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Venue Location Title</label>
                        <input type="text" name="location_title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-700" value="{{ $settings['location_title'] ?? 'Seva Sadan Chabali Ghati, Bikaner' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Venue Address Details</label>
                        <input type="text" name="location_address" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-700" value="{{ $settings['location_address'] ?? 'Bikaner, Rajasthan, India' }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Google Maps Share Link</label>
                        <input type="text" name="location_share_link" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-700" value="{{ $settings['location_share_link'] ?? 'https://share.google/C9RfibMFPueQ1JBes' }}">
                    </div>
                </div>
            </div>

            <!-- Bhojanshala Settings Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-amber-50/50 p-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Bhojanshala Settings</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">नवकारशी (सुबह) समय</label>
                        <input type="text" name="bhojanshala_morning" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_morning'] ?? '07:15 AM - 08:45 AM' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">दोपहर का भोजन समय</label>
                        <input type="text" name="bhojanshala_afternoon" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_afternoon'] ?? '11:00 AM - 02:00 PM' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">शाम का भोजन समय</label>
                        <input type="text" name="bhojanshala_evening" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_evening'] ?? '05:00 PM - सूर्यास्त तक' }}">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 mb-2">भोजनशाला का स्थान (Text)</label>
                        <input type="text" name="bhojanshala_location_text" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_location_text'] ?? 'आयोजन स्थल (Dummy Location)' }}">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 mb-2">भोजनशाला Google Map Iframe URL (src)</label>
                        <input type="text" name="bhojanshala_map_iframe" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_map_iframe'] ?? 'https://maps.google.com/maps?q=Dummy+Location&t=&z=15&ie=UTF8&loc=&output=embed' }}">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 mb-2">भोजनशाला Google Maps Share Link</label>
                        <input type="text" name="bhojanshala_map_link" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" value="{{ $settings['bhojanshala_map_link'] ?? '#' }}">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 mb-2">आवश्यक सूचनाएँ (एक पंक्ति में एक नियम लिखें)</label>
                        <textarea name="bhojanshala_rules" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700" rows="5">{{ $settings['bhojanshala_rules'] ?? "भोजनशाला में कृपया अनुशासन और शांति बनाए रखें।\nभोजन झूठा न छोड़ें, उतना ही लें जितनी आवश्यकता हो।\nसूर्यास्त के पश्चात भोजनशाला पूर्णतः बंद रहेगी। कृपया समय का ध्यान रखें।" }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3.5 text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow rounded-xl transition-all flex items-center gap-2">
                    <i class="bi bi-save2"></i> सेव करें (Save All Settings)
                </button>
            </div>
            
        </div>
    </form>

</div>
@endsection
