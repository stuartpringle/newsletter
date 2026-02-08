@extends('statamic::layout')
@section('title', 'Edit Campaign')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.campaigns.update', $campaign) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" value="{{ $campaign->name }}" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="list_id">List</label>
                <select id="list_id" name="list_id" class="input w-full">
                    <option value="">(none)</option>
                    @foreach ($lists as $list)
                        <option value="{{ $list->id }}" @selected($campaign->list_id == $list->id)>{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="template_id">Template</label>
                <select id="template_id" name="template_id" class="input w-full">
                    <option value="">(none)</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" @selected($campaign->template_id == $template->id)>{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="input w-full" value="{{ $campaign->subject }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="preview_text">Preview Text</label>
                <input type="text" id="preview_text" name="preview_text" class="input w-full" value="{{ $campaign->preview_text }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="from_name">From Name</label>
                <input type="text" id="from_name" name="from_name" class="input w-full" value="{{ $campaign->from_name }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="from_email">From Email</label>
                <input type="email" id="from_email" name="from_email" class="input w-full" value="{{ $campaign->from_email }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2" for="html">Content</label>
            <p class="text-sm text-gray-500 mb-2">Upload images in the Newsletter asset container, then paste their URL.</p>
            <input id="html" type="hidden" name="html" value="{{ old('html', $campaign->html) }}">
            <trix-editor input="html"></trix-editor>
        </div>

        <button class="btn">Save Campaign</button>
    </form>
</div>
@endsection
