@extends('layouts.member')

@section('title', 'Submit Deposit')

@section('content')
<div class="py-6 max-w-xl">
    <div class="mb-5">
        <a href="{{ route('member.deposits.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i> Back to Deposits
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Submit Deposit Request
            </h2>
            <p class="text-emerald-100 text-sm mt-0.5">Upload your payment proof for faster approval</p>
        </div>

        <form method="POST" action="{{ route('member.deposits.store') }}"
              enctype="multipart/form-data"
              x-data="depositForm()"
              @submit="submitting = true">
            @csrf

            <div class="px-6 py-5 space-y-5">

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500 font-semibold text-sm">৳</span>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               step="0.01" min="1" required
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('amount') border-red-400 bg-red-50 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('amount')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Payment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date"
                           value="{{ old('date', today()->toDateString()) }}"
                           max="{{ today()->toDateString() }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    @error('date')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([
                            'cash'  => ['label' => 'Cash',  'icon' => 'fa-money-bill-wave', 'color' => 'emerald'],
                            'bkash' => ['label' => 'bKash', 'icon' => 'fa-mobile-alt',       'color' => 'pink'],
                            'nagad' => ['label' => 'Nagad', 'icon' => 'fa-mobile-screen',    'color' => 'orange'],
                            'bank'  => ['label' => 'Bank',  'icon' => 'fa-building-columns',  'color' => 'blue'],
                        ] as $value => $info)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $value }}"
                                   {{ old('payment_method', 'cash') === $value ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="flex flex-col items-center gap-1.5 border-2 border-gray-200 rounded-xl p-3 text-center transition-all
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300">
                                <i class="fas {{ $info['icon'] }} text-lg text-gray-400 peer-checked:text-emerald-600"></i>
                                <span class="text-xs font-semibold text-gray-600">{{ $info['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('payment_method')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Payment Screenshot / Attachment --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Payment Screenshot / Receipt
                        <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                    </label>

                    {{-- Drop Zone --}}
                    <div x-ref="dropzone"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="handleDrop($event)"
                         :class="dragging ? 'border-emerald-500 bg-emerald-50 scale-[1.01]' : 'border-gray-300 bg-gray-50 hover:border-emerald-400 hover:bg-emerald-50/50'"
                         class="relative border-2 border-dashed rounded-xl transition-all duration-200 cursor-pointer"
                         @click="$refs.fileInput.click()">

                        {{-- Empty state --}}
                        <div x-show="!preview" class="flex flex-col items-center justify-center py-10 px-4 text-center">
                            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-cloud-arrow-up text-emerald-600 text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">
                                <span class="text-emerald-600">Click to upload</span> or drag & drop
                            </p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WEBP, PDF · Max 5MB</p>
                        </div>

                        {{-- Preview --}}
                        <div x-show="preview" x-cloak class="relative">
                            {{-- Image preview --}}
                            <template x-if="previewType === 'image'">
                                <div class="relative">
                                    <img :src="preview" alt="Preview"
                                         class="w-full max-h-64 object-contain rounded-xl p-2">
                                    <div class="absolute inset-0 bg-black/0 hover:bg-black/20 rounded-xl transition-all flex items-center justify-center opacity-0 hover:opacity-100">
                                        <span class="bg-white/90 text-gray-700 text-xs font-medium px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-pencil mr-1"></i> Change Image
                                        </span>
                                    </div>
                                </div>
                            </template>

                            {{-- PDF preview --}}
                            <template x-if="previewType === 'pdf'">
                                <div class="flex items-center gap-4 px-5 py-6">
                                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate" x-text="fileName"></p>
                                        <p class="text-xs text-gray-400" x-text="fileSize"></p>
                                    </div>
                                    <span class="text-xs text-emerald-600 font-medium">Click to change</span>
                                </div>
                            </template>

                            {{-- Remove button --}}
                            <button type="button"
                                    @click.stop="removeFile()"
                                    class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors z-10">
                                <i class="fas fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Hidden file input --}}
                    <input type="file" name="attachment" x-ref="fileInput"
                           accept="image/jpeg,image/png,image/gif,image/webp,application/pdf"
                           @change="handleFileSelect($event)"
                           class="hidden">

                    {{-- File info bar --}}
                    <div x-show="preview" x-cloak class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-circle-check text-emerald-500"></i>
                        <span x-text="fileName"></span>
                        <span class="text-gray-300">·</span>
                        <span x-text="fileSize"></span>
                    </div>

                    @error('attachment')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes
                        <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    <textarea name="notes" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                              placeholder="Any reference number or additional information...">{{ old('notes') }}</textarea>
                </div>

            </div>

            {{-- Info notice --}}
            <div class="mx-6 mb-4 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
                <i class="fas fa-circle-info mt-0.5 flex-shrink-0 text-amber-500"></i>
                <span>Your request stays <strong>pending</strong> until the admin approves it. Uploading a payment screenshot helps speed up approval.</span>
            </div>

            {{-- Submit --}}
            <div class="px-6 pb-6">
                <button type="submit"
                        :disabled="submitting"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-all flex items-center justify-center gap-2 text-sm">
                    <span x-show="!submitting"><i class="fas fa-paper-plane mr-1"></i> Submit Deposit Request</span>
                    <span x-show="submitting" x-cloak>
                        <i class="fas fa-spinner fa-spin mr-1"></i> Submitting...
                    </span>
                </button>
                <a href="{{ route('member.deposits.index') }}"
                   class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function depositForm() {
    return {
        dragging:    false,
        preview:     null,
        previewType: null,
        fileName:    '',
        fileSize:    '',
        submitting:  false,

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.processFile(file);
        },

        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;

            // Sync with hidden input
            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.fileInput.files = dt.files;

            this.processFile(file);
        },

        processFile(file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('File is too large. Maximum allowed size is 5MB.');
                return;
            }

            this.fileName = file.name;
            this.fileSize = this.formatBytes(file.size);

            if (file.type.startsWith('image/')) {
                this.previewType = 'image';
                const reader = new FileReader();
                reader.onload = (e) => { this.preview = e.target.result; };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                this.previewType = 'pdf';
                this.preview     = 'pdf';
            }
        },

        removeFile() {
            this.preview     = null;
            this.previewType = null;
            this.fileName    = '';
            this.fileSize    = '';
            this.$refs.fileInput.value = '';
        },

        formatBytes(bytes) {
            if (bytes < 1024)       return bytes + ' B';
            if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },
    }
}
</script>
@endpush
