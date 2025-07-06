const $ = jQuery;

class SheetOptions {
    selectors = {
        container: '.sheet-options-tab',
        options: '[type=checkbox].option-field',
        saveButton: '.save-sheet-options',
        successMessage: '.success-message',
        failedMessage: '.failed-message',
    }

    elements = {}

    constructor() {
       document.addEventListener('DOMContentLoaded', () => {
           this.refreshElements();
           this.handleSaveOptions();
       });
    }

    refreshElements() {
        this.elements.container = document.querySelector(this.selectors.container);
        this.elements.saveButton = this.elements.container.querySelector(this.selectors.saveButton);
        this.elements.successMessage = this.elements.container.querySelector(this.selectors.successMessage);
        this.elements.failedMessage = this.elements.container.querySelector(this.selectors.failedMessage);
    }

    handleSaveOptions() {
        this.elements.saveButton.addEventListener('click', () => {
            this.elements.container.classList.add('loading');

            let options = {};
            this.elements.container.querySelectorAll(this.selectors.options).forEach((option) => {
                options[option.name] = option.checked;
            });

            $.ajax({
                url: AfzGsObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'afzaliwp_save_sheet_options',
                    form_id: this.elements.container.dataset.formId,
                    options: options
                },
                dataType: 'json',
                success: (response) => {
                    this.elements.container.classList.remove('loading');

                    if (response.success) {
                        this.elements.successMessage.classList.remove('hidden');
                        this.elements.failedMessage.classList.add('hidden');
                    } else {
                        this.elements.successMessage.classList.add('hidden');
                        this.elements.failedMessage.classList.remove('hidden');
                        this.elements.failedMessage.querySelector('span').innerText = response.data;
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    this.elements.container.classList.remove('loading');
                    this.elements.failedMessage.classList.remove('hidden');
                    this.elements.failedMessage.querySelector('span').innerText = 'Error: ' + jqXHR.status;
                    this.elements.successMessage.classList.add('hidden');
                }
            });
        });
    }
}

export default new SheetOptions();