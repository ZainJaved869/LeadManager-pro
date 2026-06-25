<div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">AI Email Writer</h3>
        <form id="emailForm" onsubmit="generateEmail(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Select Lead (optional)</label>
                    <select id="email_lead_id" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="">None</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->company ?? 'No company' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Context <span class="text-red-500">*</span></label>
                    <textarea id="email_context" rows="4" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500" placeholder="Describe what you sell, who you're targeting, or any specific details...">I sell website development services</textarea>
                </div>
                <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-wand-magic-sparkles mr-2"></i> Generate Email
                </button>
            </div>
        </form>

        <div id="emailResult" class="mt-6 hidden">
            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-slate-700">Generated Email</h4>
                    <button onclick="copyToClipboard('emailContent')" class="text-sm text-yellow-600 hover:text-yellow-700">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div id="emailContent" class="text-slate-700 whitespace-pre-wrap text-sm leading-relaxed"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function generateEmail(e) {
        e.preventDefault();
        const context = document.getElementById('email_context').value;
        const leadId = document.getElementById('email_lead_id').value;

        if (!context.trim()) {
            alert('Please enter some context.');
            return;
        }

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';

        fetch('{{ route("ai.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ context, lead_id: leadId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('emailContent').innerText = data.content;
                document.getElementById('emailResult').classList.remove('hidden');
            } else {
                alert('Error generating email.');
            }
        })
        .catch(err => alert('Error: ' + err.message))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-wand-magic-sparkles mr-2"></i> Generate Email';
        });
    }
</script>