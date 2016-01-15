/**
 * Created by myslyvyi on 14.01.2016.
 */
/** JS SamsonCMS External picture handler */
var SamsonCMS_InputExternalPicture = function(field)
{
    // Value for empty field
    var textInput = s('input[name="inputText"]',field).val();
    // Value for clear confirm
    var textClearConfirm = s('input[name="clearConfirm"]',field).val();
    // Parents element for fields with value and information
    var parentsFields = s('.__externalPictureField', field);
    // Default value
    var defaultValue = 'http://';

    /** Clear field value in DB handler */
    s('.removeExternalPicture', field).click(function(btn) {
        // If we are not deleting right now - ask confirmation
        if (confirm(textClearConfirm)) {
            // Get input field block
            var parent = btn.parent('.__inputfield');

            // Create loader
            var loader = new Loader(parent, {type: 'absolute', top: 1, left: 1});
            // Show loader
            loader.show();
            // Perform ajax file delete
            s.ajax(btn.a('href'), function(response)
            {
                //Parse response
                response = JSON.parse(response);

                if (response.status) {
                    // Remove loader
                    loader.remove();
                    // Hide current preview images
                    s('.imagePreview', field).hide();
                    clearAndShowFieldValue();
                }
            });
        }
    }, true, true);

    /** Catch response input text*/
    SamsonCMS_InputText(field, function(response) {
        //Parse response
        response = JSON.parse(response);

        if (response.status) {
            if (s('.__input', parentsFields).val() != defaultValue) {
                s('.imagePreview>img').a('src', s('.__input', parentsFields).val());
                // Hide current preview images
                s('.imagePreview', field).show();
                // Show input field
                parentsFields.hide();
            }
        } else {
            clearAndShowFieldValue(this);
        }
    });

    /** Clear field value */
    function clearAndShowFieldValue()
    {
        s('.__input', parentsFields).val(defaultValue);
        s('.__hidden', parentsFields).val(defaultValue);
        // Set information for input
        s('span', parentsFields).html(textInput);
        // Add class for input field
        parentsFields.addClass('__empty');
        // Show input field
        parentsFields.show();
    }
};

// Bind input
SamsonCMS_Input.bind(SamsonCMS_InputExternalPicture, '.__externalPicture');