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
        <a href="{{ route('admin.helplines.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 flex items-center justify-center transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ isset($helpline) ? 'हेल्पलाइन अपडेट करें' : 'नया हेल्पलाइन जोड़ें' }}</h1>
            <p class="text-sm text-slate-500 font-medium">Fill in the details below</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <form action="{{ isset($helpline) ? route('admin.helplines.update', $helpline->id) : route('admin.helplines.store') }}" method="POST">
            @csrf
            @if(isset($helpline))
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
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">श्रेणी (Category) <span class="text-rose-500">*</span></label>
                        <select name="category" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium" required>
                            <option value="">-- श्रेणी चुनें --</option>
                            <option value="सामान्य सहायता" {{ old('category', $helpline->category ?? '') == 'सामान्य सहायता' ? 'selected' : '' }}>सामान्य सहायता</option>
                            <option value="आपातकालीन सहायता" {{ old('category', $helpline->category ?? '') == 'आपातकालीन सहायता' ? 'selected' : '' }}>आपातकालीन सहायता</option>
                            <option value="चिकित्सा सेवा" {{ old('category', $helpline->category ?? '') == 'चिकित्सा सेवा' ? 'selected' : '' }}>चिकित्सा सेवा</option>
                            <option value="व्हाट्सएप संपर्क" {{ old('category', $helpline->category ?? '') == 'व्हाट्सएप संपर्क' ? 'selected' : '' }}>व्हाट्सएप संपर्क</option>
                            <option value="कार्यालय संपर्क" {{ old('category', $helpline->category ?? '') == 'कार्यालय संपर्क' ? 'selected' : '' }}>कार्यालय संपर्क</option>
                            <option value="भोजनशाला संपर्क" {{ old('category', $helpline->category ?? '') == 'भोजनशाला संपर्क' ? 'selected' : '' }}>भोजनशाला संपर्क</option>
                            <option value="परिवहन" {{ old('category', $helpline->category ?? '') == 'परिवहन' ? 'selected' : '' }}>परिवहन</option>
                        </select>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">प्रतिनिधि नाम <span class="text-rose-500">*</span></label>
                        <input type="text" name="representative_name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" value="{{ old('representative_name', $helpline->representative_name ?? '') }}" placeholder="उदा: रमेश कुमार" required>
                    </div>

                    <!-- Number -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">नंबर <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <input type="text" name="number" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" value="{{ old('number', $helpline->number ?? '') }}" placeholder="+91 9876543210" required>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">प्रकार (Type)</label>
                        <select name="type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium">
                            <option value="mobile" {{ old('type', $helpline->type ?? '') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            <option value="landline" {{ old('type', $helpline->type ?? '') == 'landline' ? 'selected' : '' }}>Landline</option>
                            <option value="whatsapp" {{ old('type', $helpline->type ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp Only</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 p-5 bg-indigo-50/50 border border-indigo-100 rounded-xl flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">होमपेज मेडिकल इमरजेंसी?</h4>
                        <p class="text-xs text-slate-500 mt-0.5">क्या यह नंबर होमपेज के मेडिकल मॉडल में दिखना चाहिए?</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_home_medical" value="0">
                        <input type="checkbox" name="is_home_medical" value="1" class="sr-only peer" {{ old('is_home_medical', $helpline->is_home_medical ?? 0) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <input type="hidden" name="status" value="0">
                    <input class="w-5 h-5 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="status" value="1" id="statusCheck" {{ old('status', $helpline->status ?? 1) ? 'checked' : '' }}>
                    <label class="text-sm font-bold text-slate-700 cursor-pointer" for="statusCheck">
                        सक्रिय रखें (Active)
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-slate-50/80 p-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.helplines.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                    रद्द करें (Cancel)
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow rounded-xl transition-all flex items-center gap-2">
                    <i class="bi bi-save2"></i> {{ isset($helpline) ? 'अपडेट करें (Update)' : 'सेव करें (Save)' }}
                </button>
            </div>
            
        </form>
    </div>

</div>
@endsection
