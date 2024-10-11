<x-dialog-box id="delete-custom-attribute-{{ $app->aid }}" dialogTitle="Confirm removal of attribute"
              class="custom-attributes-dialog">
    <div class="data-container">
        <span>
            Are you sure you want to delete the attribute: <strong id="delete-attribute-name"></strong>: <strong id="delete-attribute-value">attribute value</strong>
        </span>
    </div>
    <div class="delete-attribute-bottom-shadow-container button-container">
        <form class="confirm-user-deletion-request-form" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="attribute_key" value="">

            <button type="submit" class="confirm-deletion-btn btn primary">Confirm</button>
            <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
        </form>
    </div>
</x-dialog-box>
