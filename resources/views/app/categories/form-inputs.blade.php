@php $editing = isset($category) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="Name"
            value="{{ old('name', ($editing ? $category->name : '')) }}"
            maxlength="255"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="slug"
            label="Slug"
            value="{{ old('slug', ($editing ? $category->slug : '')) }}"
            maxlength="255"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>
