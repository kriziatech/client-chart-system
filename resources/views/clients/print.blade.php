<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Chart - {{ $client->first_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: white;
            -webkit-print-color-adjust: exact;
            padding: 10mm;
            font-family: sans-serif;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
        }

        .section-header {
            background-color: #0F766E !important;
            color: white !important;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 0.9rem;
            margin-top: 10px;
            margin-bottom: 5px;
            -webkit-print-color-adjust: exact;
        }

        table th {
            background-color: #F3F4F6 !important;
            -webkit-print-color-adjust: exact;
        }

        .check-box {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 4px;
        }

        .checked {
            background: #000;
        }
    </style>
</head>

<body>
    <div class="print-container text-xs">
        <h1 class="text-2xl font-bold text-center text-teal-800 uppercase mb-4 border-b-2 border-teal-800 pb-2">Client
            Chart / Project Work Sheet</h1>

        <!-- 1. Client Info -->
        <table class="w-full border-collapse border border-gray-400 mb-4">
            <tr>
                <td class="p-1 border border-gray-400 w-1/2"><span class="font-bold">Client Name:</span> {{
                    $client->first_name }} {{ $client->last_name }}</td>
                <td class="p-1 border border-gray-400 w-1/4"><span class="font-bold">Start Date:</span> {{
                    $client->start_date }}</td>
                <td class="p-1 border border-gray-400 w-1/4"><span class="font-bold">File No:</span> {{
                    $client->file_number }}</td>
            </tr>
            <tr>
                <td class="p-1 border border-gray-400"><span class="font-bold">Address:</span> {{ $client->address }}
                </td>
                <td class="p-1 border border-gray-400"><span class="font-bold">Delivery Date:</span> {{
                    $client->delivery_date }}</td>
                <td class="p-1 border border-gray-400"><span class="font-bold">Mobile:</span> {{ $client->mobile }}</td>
            </tr>
            <tr>
                <td colspan="3" class="p-1 border border-gray-400"><span class="font-bold">Work Description:</span> {{
                    $client->work_description }}</td>
            </tr>
        </table>

        <!-- 2. Checklist -->
        <div class="section-header">WORK CHECKLIST</div>
        <div class="grid grid-cols-5 gap-1 mb-4 border border-gray-400 p-2">
            @forelse($client->checklistItems as $item)
            <div class="flex items-center">
                <span class="check-box {{ $item->is_checked ? 'checked' : '' }}"></span>
                <span>{{ $item->name }}</span>
            </div>
            @empty
            <div class="col-span-5 text-center text-gray-500">No checklist items.</div>
            @endforelse
        </div>

        <!-- 3. Site Info & 4. Permissions -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="border border-gray-400 p-2">
                <div class="font-bold border-b border-gray-400 mb-2">SITE INFORMATION</div>
                <p class="mb-1"><span class="font-bold">Signed By:</span> {{ $client->siteInfo->signed_by }}</p>
                <p class="mb-1"><span class="font-bold">Site Facts:</span> {{ $client->siteInfo->site_facts }}</p>
                <p class="mb-1"><span class="font-bold">Instructions:</span><br> {{
                    $client->siteInfo->working_instructions }}</p>
            </div>
            <div class="border border-gray-400 p-2">
                <div class="font-bold border-b border-gray-400 mb-2">PERMISSIONS</div>
                <div class="flex items-center mb-1">
                    <span class="check-box {{ $client->permission->work_permit ? 'checked' : '' }}"></span> Work Permit
                </div>
                <div class="flex items-center">
                    <span class="check-box {{ $client->permission->gate_pass ? 'checked' : '' }}"></span> Gate Pass
                </div>
            </div>
        </div>

        <!-- 5. Comments -->
        <div class="section-header">COMMENTS SECTION</div>
        <table class="w-full border-collapse border border-gray-400 mb-4 text-[10px]">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-400 p-1 w-16">Date</th>
                    <th class="border border-gray-400 p-1 w-24">Work</th>
                    <th class="border border-gray-400 p-1 w-12">Initials</th>
                    <th class="border border-gray-400 p-1">Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->comments as $comment)
                <tr>
                    <td class="border border-gray-400 p-1">{{ $comment->date }}</td>
                    <td class="border border-gray-400 p-1">{{ $comment->work }}</td>
                    <td class="border border-gray-400 p-1">{{ $comment->initials }}</td>
                    <td class="border border-gray-400 p-1">{{ $comment->comment }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <!-- 6. Payments -->
        <div class="section-header">PAYMENTS SECTION</div>
        <table class="w-full border-collapse border border-gray-400 mb-4 text-[10px]">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-400 p-1 w-32">Name</th>
                    <th class="border border-gray-400 p-1 w-24">Role</th>
                    <th class="border border-gray-400 p-1 w-20">Amount</th>
                    <th class="border border-gray-400 p-1 w-20">Date</th>
                    <th class="border border-gray-400 p-1">Purpose</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->payments as $payment)
                <tr>
                    <td class="border border-gray-400 p-1">{{ $payment->name }}</td>
                    <td class="border border-gray-400 p-1">{{ $payment->role }}</td>
                    <td class="border border-gray-400 p-1">₹{{ $payment->amount }}</td>
                    <td class="border border-gray-400 p-1">{{ $payment->date }}</td>
                    <td class="border border-gray-400 p-1">{{ $payment->purpose }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <!-- Section 7: Tasks -->
        <div class="section-header">TASKS & TIMELINE</div>
        <table class="w-full border-collapse border border-gray-400 mb-4 text-[10px]">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-400 p-1 w-1/3">Task</th>
                    <th class="border border-gray-400 p-1">Assigned To</th>
                    <th class="border border-gray-400 p-1">Deadline</th>
                    <th class="border border-gray-400 p-1">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->tasks as $task)
                <tr>
                    <td class="border border-gray-400 p-1">{{ $task->description }}</td>
                    <td class="border border-gray-400 p-1">{{ $task->assigned_to }}</td>
                    <td class="border border-gray-400 p-1">{{ $task->deadline ? $task->deadline->format('d M, Y') : '-'
                        }}</td>
                    <td class="border border-gray-400 p-1">{{ $task->status }}</td>
                </tr>
                @endforeach
                <!-- Empty rows for print if needed -->
                @if($client->tasks->isEmpty())
                <tr>
                    <td colspan="4" class="border border-gray-400 p-2 text-center text-gray-500">No tasks assigned.</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="text-center text-xs text-gray-400 mt-8">
            generated by Krizia Technologies
        </div>
    </div>
    <script>
        rint();
    </script>
</body>

</html>