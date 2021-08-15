@php $editing = isset($comment) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.textarea
            name="content"
            label="Content"
            maxlength="255"
            required
            >{{ old('content', ($editing ? $comment->content : ''))
            }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="author_id" label="Author" required>
            @php $selected = old('author_id', ($editing ? $comment->author_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="post_id" label="Post" required>
            @php $selected = old('post_id', ($editing ? $comment->post_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Post</option>
            @foreach($posts as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="score"
            label="Score"
            value="{{ old('score', ($editing ? $comment->score : '')) }}"
            max="255"
            required
        ></x-inputs.number>
    </x-inputs.group>
</div>
