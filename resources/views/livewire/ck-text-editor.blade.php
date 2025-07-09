<div>
    <textarea id="ckeditor" wire:model.lazy="content"></textarea>

    <!-- Display the content -->
    <div class="mt-4">
        <h3>Live Preview:</h3>
        <div>{!! $content !!}</div>
    </div>
</div>