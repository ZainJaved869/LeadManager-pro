<div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">AI Follow-up Generator</h3>
        <form id="followupForm" onsubmit="generateFollowup(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Select Lead (optional)</label>
                    <select id="followup_lead_id" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="">None</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->company ?? 'No company' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Context <span class="text-red-500">*</span></label>
                    <textarea id="followup_context" rows="3" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500" placeholder="Describe the conversation so far...">We had a great initial call and they seemed interested in our product.</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Previous Email Sent (optional)</label>
                    <textarea id="followup_previous" rows="3" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500" placeholder="Paste the previous email you sent..."></textarea>
                </div>
                <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-wand-magic-sparkles mr-2"></i> Generate Follow-up
                </button>
            </div>
        </form>

        <div id="followupResult" class="mt-6 hidden">
            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-slate-700">Generated Follow-up</h4>
                    <button onclick="copyToClipboard('followupContent')" class="text-sm text-yellow-600 hover:text-yellow-700">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div id="followupContent" class="text-slate-700 whitespace-pre-wrap text-sm leading-relaxed"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function generateFollowup(e) {
        e.preventDefault();
        const context = document.getElementById('followup_context').value;
        const previous = document.getElementById('followup_previous').value;
        const leadId = document.getElementById('followup_lead_id').value;

        if (!context.trim()) {
            alert('Please enter some context.');
            return;
        }

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';

        fetch('{{ route("ai.followup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ context, previous_email: previous, lead_id: leadId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('followupContent').innerText = data.content;
                document.getElementById('followupResult').classList.remove('hidden');
            } else {
                alert('Error generating follow-up.');
            }
        })
        .catch(err => alert('Error: ' + err.message))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-wand-magic-sparkles mr-2"></i> Generate Follow-up';
        });
    }
</script>