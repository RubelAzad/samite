<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Receipt #{{ $deposit->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Noto Sans', 'sans-serif'] } } }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .receipt-container { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-8 font-sans">
    <div class="no-print fixed top-4 right-4 flex gap-2">
        <button onclick="downloadPDF()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
            <i class="fas fa-file-pdf mr-2"></i> Download PDF
        </button>
        <button onclick="downloadImage()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-image mr-2"></i> Download Image
        </button>
        <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-print mr-2"></i> Print
        </button>
    </div>

    <div id="receipt" class="receipt-container bg-white rounded-xl shadow-xl w-full max-w-md p-8">
        <!-- Header -->
        <div class="text-center mb-8 pb-6 border-b-2 border-emerald-100">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Samite Logo" class="w-12 h-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <i class="fas fa-university text-emerald-600 text-2xl" style="display:none;"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Money Receipt</h1>
            <p class="text-sm text-emerald-700 font-medium mt-2">আত্মনির্ভর যৌথ উদ্যোগ</p>
        </div>

        <!-- Receipt Number & Date -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Receipt No</p>
                <p class="font-bold text-gray-800">#{{ $deposit->id }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-xs uppercase tracking-wide">Date</p>
                <p class="font-bold text-gray-800">{{ $deposit->date->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Member Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Received From</p>
            <p class="font-semibold text-gray-800">{{ $deposit->member->user->name ?? 'N/A' }}</p>
            <p class="text-gray-500 text-sm">{{ $deposit->member->member_code ?? '' }}</p>
        </div>

        <!-- Amount -->
        <div class="text-center mb-6">
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-2">Amount Received</p>
            <div class="text-4xl font-bold text-emerald-700">৳{{ number_format($deposit->amount, 2) }}</div>
            <p class="text-gray-500 text-sm mt-2">
                ({{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }})
            </p>
        </div>

        <!-- Details Table -->
        <table class="w-full text-sm mb-6 border-t border-b border-gray-200">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-3 text-gray-500">Payment Method</td>
                    <td class="py-3 text-right font-medium text-gray-800 capitalize">{{ $deposit->payment_method }}</td>
                </tr>
                @if($deposit->bank_account_id)
                <tr>
                    <td class="py-3 text-gray-500">Bank Account</td>
                    <td class="py-3 text-right font-medium text-gray-800">{{ $deposit->bankAccount->name ?? '' }}</td>
                </tr>
                @endif
                @if($deposit->notes)
                <tr>
                    <td class="py-3 text-gray-500">Notes</td>
                    <td class="py-3 text-right text-gray-700">{{ $deposit->notes }}</td>
                </tr>
                @endif
                <tr>
                    <td class="py-3 text-gray-500">Status</td>
                    <td class="py-3 text-right">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($deposit->status) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Approved By -->
        @if($deposit->isApproved() && $deposit->approvedBy)
        <div class="flex items-center justify-between pt-6 border-t border-gray-200 mb-6">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Approved By</p>
                    <p class="font-semibold text-gray-800">{{ $deposit->approvedBy->name }}</p>
                    <p class="text-xs text-gray-500">{{ $deposit->approved_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-xs uppercase tracking-wide">Generated On</p>
                <p class="text-sm font-medium text-gray-700">{{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const receipt = document.getElementById('receipt');

            const canvas = await html2canvas(receipt, {
                scale: 2,
                useCORS: true,
                logging: false
            });

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'px',
                format: [canvas.width, canvas.height]
            });

            pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
            pdf.save('Deposit_Receipt_#{{ $deposit->id }}.pdf');
        }

        async function downloadImage() {
            const receipt = document.getElementById('receipt');

            const canvas = await html2canvas(receipt, {
                scale: 2,
                useCORS: true,
                logging: false
            });

            const link = document.createElement('a');
            link.download = 'Deposit_Receipt_#{{ $deposit->id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }
    </script>
</body>
</html>
