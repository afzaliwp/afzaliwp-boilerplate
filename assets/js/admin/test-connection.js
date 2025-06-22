const $ = jQuery;

class TestConnection {
    selectors = {
        connectionTab: '.connection-tab',
        testForm: '.connection-tab form',
        urlInput: '.connection-tab form [name="gs-scripts-url"]',
        formId: '.connection-tab form [name="form-id"]',
        alertSuccess: '.connection-tab .success-message',
        alertFailed: '.connection-tab .failed-message',
        successMessage: '.connection-tab .success-message span',
        failedMessage: '.connection-tab .failed-message span',
    }

    elements = {}

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.refreshElements();
            this.handleTestUrl();
        });
    }

    refreshElements() {
        this.elements = {
            connectionTab: document.querySelector(this.selectors.connectionTab),
            testForm: document.querySelector(this.selectors.testForm),
            alertSuccess: document.querySelector(this.selectors.alertSuccess),
            alertFailed: document.querySelector(this.selectors.alertFailed),
            successMessage: document.querySelector(this.selectors.successMessage),
            failedMessage: document.querySelector(this.selectors.failedMessage),
        }
    }

    handleTestUrl() {
        this.elements.testForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const url = document.querySelector(this.selectors.urlInput).value;
            const formId = document.querySelector(this.selectors.formId).value;
            this.elements.testForm.classList.add('loading');
            $.ajax({
                url: AfzGsObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'afzaliwp_test_connection',
                    url: url,
                    form_id: formId,
                },
                success: (response) => {
                    if (response.success) {
                        this.alertSuccess(response.data);
                    } else {
                        this.alertFailed(response.data);
                    }
                    this.elements.testForm.classList.remove('loading');
                },
                error: (jqXHR) => {
                    console.log(jqXHR);
                    this.alertFailed('Error: ' + jqXHR.status);
                    this.elements.testForm.classList.remove('loading');
                }
            });


        });
    }

    alertSuccess(response) {
        this.elements.successMessage.innerHTML = response;
        this.elements.alertFailed.classList.add('hidden');
        this.elements.alertSuccess.classList.remove('hidden');
    }

    alertFailed(response) {
        this.elements.failedMessage.innerHTML = response;
        this.elements.alertSuccess.classList.add('hidden');
        this.elements.alertFailed.classList.remove('hidden');
    }
}

export default new TestConnection();