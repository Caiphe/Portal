<x-dialog-box id="edit-custom-attributes-{{ $app->aid }}" dialogTitle="Edit Attribute"
              class="custom-attributes-dialog">

    <form id="custom-attribute-form" method="POST"
          class="status-dialog-form">
        <div class="attribute-form-container-data">
            {{--@method('POST')--}}
            @csrf

            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" onchange="handleAttributeTypeChange()">
                    <option value="string">String</option>
                    <option value="number">CSV String Array</option>
                    <option value="boolean">Boolean</option>
                </select>
                <p id="type-description">A string attribute is the default type of attribute and only accepts a text
                    value without special characters or spaces.</p>
            </div>
            <div id="attribute-fields-container">
                <!-- Name Field -->
                <div class="form-group" id="name-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" name="attribute[name]" placeholder="The name of the attribute"
                           required>
                    <p class="error-message" id="name-error" style="color: red; display: none;"></p>
                </div>

                <!-- Value Field -->
                <div class="form-group" id="value-field">
                    <label for="value">Value</label>
                    <input type="text" id="value" class="form-control" name="attribute[value]" placeholder="The value of the attribute"
                           required>
                    <p class="error-message" id="value-error" style="color: red; display: none;"></p>
                </div>

                <!-- Number Textarea Field (hidden initially) -->
                <div class="form-group" id="number-field" style="display: none;">
                    <label for="number-value">Value</label>
                    <textarea id="number-value" class="form-control" name="attribute[value]"
                              placeholder="Type comma separated values or use spaces to separate values"></textarea>
                    <div id="tag-container" class="tag-container"></div>
                </div>

                <!-- Boolean Select Field (hidden initially) -->
                <div class="form-group" id="boolean-field" style="display: none;">
                    <label for="boolean-value">Value</label>
                    <select id="boolean-value" name="attribute[value]">
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bottom-shadow-container button-container">
            <button type="submit" class="btn-attribute btn-confirm">Confirm</button> <!-- Removed 'disabled' class -->
            <button type="button" class="btn black-bordered mr-10 close-add-teammate-btn"
                    onclick="closeDialogBox(this);">CANCEL
            </button>
        </div>
    </form>
</x-dialog-box>
