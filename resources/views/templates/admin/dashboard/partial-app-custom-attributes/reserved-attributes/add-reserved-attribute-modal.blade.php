<x-dialog-box id="reserved-attributes-{{ $app->aid }}"
              dialogTitle="Add a reserved attribute"
              class="custom-attributes-dialog">

    <form id="add-reserved-custom-attribute-form" method="POST"
          {{--action="{{ route('app.save-custom-attributes', $app->aid) }}"--}}
          class="status-dialog-form">
        <div class="attribute-form-container-data">
            @method('POST')
            @csrf
            <div class="form-group">
                <label for="type">Attribute</label>
                <select id="type" name="type">
                    <option value="senderMsisdn">senderMsisdn</option>
                    <option value="originalChannelIDs">originalChannelIDs</option>
                    <option value="partnerName">partnerName</option>
                    <option value="permittedSenderIDs">PermittedSenderIDs</option>
                    <option value="permittedPlanIDs">PermittedPlanIDs</option>
                    <option value="autoRenewAllowed">AutoRenewAllowed</option>
                </select>
                <p id="type-description" style="display: none;color: #969696"></p>
            </div>

            <!-- Name Field -->
            <div class="form-group" id="name-field" style="display: none">
                <label for="name">Name</label>
                <input type="hidden" id="name" name="attribute[name]" readonly placeholder="The name of the attribute"
                       required>
                <p class="error-message" id="name-error" style="color: red; display: none;"></p>
            </div>

            <!-- Value Field -->
            <div class="form-group" id="value-field">
                <label for="value">Value</label>
                <input type="text" id="value" name="attribute[value]" placeholder="The value of the attribute"
                       required>
                <p class="error-message" id="value-error" style="color: red; display: none;"></p>
            </div>

            <!-- Number Textarea Field (hidden initially) -->
            <div class="form-group" id="number-field" style="display: none;">
                <label for="number-value">Value</label>
                <span id="value-description" style="display: none;color: #0c678f"></span>
                <textarea id="number-value" name="attribute[value]"
                          placeholder="Type comma or press enter to create tag values"></textarea>
                <p class="error-message" id="tags-error" style="color: red; display: none;"></p>
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

        <div class="bottom-shadow-container button-container">
            <button type="submit" class="btn-attribute btn-confirm disabled">Confirm</button>
            <button type="button" class="btn black-bordered mr-10 close-add-teammate-btn"
                    onclick="closeDialogBox(this);">CANCEL
            </button>
        </div>
    </form>
</x-dialog-box>
