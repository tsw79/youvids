/**
 * Filesize - JQuery Validation extension
 *
 * dependency: jquery.validation plugin
 * @ref  https://applerinquest.com/how-to-validate-a-form-with-jquery-validation-plugin/
 * @link https://stackoverflow.com/questions/33096591/validate-file-extension-and-size-before-submitting-form
 * @link https://github.com/jquery-validation/jquery-validation/releases/tag/1.13.1
 */
$.validator.addMethod(
    'filesize',
    /**
     * Returns true if the UploadedFile's filesize is less than or equals to the max file size.
     * @param value      Value of the element (file name)
     * @param element    Element to validate (<input>)
     * @param param      Size (en bytes)
     * @returns {*|boolean}
     */
    function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    },
    'File size must be less than {0} bytes.'
);



