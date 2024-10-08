<x-dialog-box id="delete-custom-attribute-{{ $app->aid }}" dialogTitle="Delete App Attribute"
              class="custom-attributes-dialog">

    <div class="data-container" style="text-align: center">
        <span>
            Are you sure you want to delete the
           Attribute Name and Value: <br>{<strong id="delete-attribute-name"></strong>: <strong id="delete-attribute-value">attribute value</strong>}
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
