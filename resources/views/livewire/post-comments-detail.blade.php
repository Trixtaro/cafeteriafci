<div>
    <div>
        @can('create', App\Models\Comment::class)
        <button class="button" wire:click="newComment">
            <i class="mr-1 icon ion-md-add text-primary"></i>
            @lang('crud.common.new')
        </button>
        @endcan @can('delete-any', App\Models\Comment::class)
        <button
            class="button button-danger"
             {{ empty($selected) ? 'disabled' : '' }} 
            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
            wire:click="destroySelected"
        >
            <i class="mr-1 icon ion-md-trash text-primary"></i>
            @lang('crud.common.delete_selected')
        </button>
        @endcan
    </div>

    <x-modal wire:model="showingModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">{{ $modalTitle }}</div>

            <div class="mt-5">
                <div>
                    <x-inputs.group class="w-full">
                        <x-inputs.textarea
                            name="comment.content"
                            label="Content"
                            wire:model="comment.content"
                            maxlength="255"
                        ></x-inputs.textarea>
                    </x-inputs.group>

                    <x-inputs.group class="w-full">
                        <x-inputs.select
                            name="comment.author_id"
                            label="Author"
                            wire:model="comment.author_id"
                        >
                            <option value="null" disabled>Please select the User</option>
                            @foreach($postUsers as $value => $label)
                            <option value="{{ $value }}"  >{{ $label }}</option>
                            @endforeach
                        </x-inputs.select>
                    </x-inputs.group>

                    <x-inputs.group class="w-full">
                        <x-inputs.number
                            name="comment.score"
                            label="Score"
                            wire:model="comment.score"
                            max="255"
                        ></x-inputs.number>
                    </x-inputs.group>
                </div>
            </div>

            @if($editing) @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                @lang('crud.common.cancel')
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="save"
            >
                <i class="mr-1 icon ion-md-save"></i>
                @lang('crud.common.save')
            </button>
        </div>
    </x-modal>

    <div class="block w-full overflow-auto scrolling-touch mt-4">
        <table class="w-full max-w-full mb-4 bg-transparent">
            <thead class="text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left w-1">
                        <input
                            type="checkbox"
                            wire:model="allSelected"
                            wire:click="toggleFullSelection"
                            title="{{ trans('crud.common.select_all') }}"
                        />
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.post_comments.inputs.content')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.post_comments.inputs.author_id')
                    </th>
                    <th class="px-4 py-3 text-right">
                        @lang('crud.post_comments.inputs.score')
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach ($comments as $comment)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-3 text-left">
                        <input
                            type="checkbox"
                            value="{{ $comment->id }}"
                            wire:model="selected"
                        />
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $comment->content ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($comment->author)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $comment->score ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right" style="width: 134px;">
                        <div
                            role="group"
                            aria-label="Row Actions"
                            class="relative inline-flex align-middle"
                        >
                            @can('update', $comment)
                            <button
                                type="button"
                                class="button"
                                wire:click="editComment({{ $comment->id }})"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <div class="mt-10 px-4">{{ $comments->render() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
