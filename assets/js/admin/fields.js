const $ = jQuery;

class Fields {
    selectors = {
        container: '.fields-mapping-tab',
        fieldsContainer: '.flex.flex-col.gap-1',
        fieldRow: '[data-field-id]',
        addCustomButton: '.afz-button-secondary-accent',
        saveFieldsButton: '.save-fields-button',
        orderUp: '.afzaliwp-arrow-up',
        orderDown: '.afzaliwp-arrow-down',
        orderNumber: '.order-control .body3',
        customFieldTemplate: '#custom_field_row',
        checkbox: 'input[type="checkbox"]',
        removeButton: '.remove-row',
        clickedClass: 'field-clicked',
        successMessage: '.fields-mapping-tab .success-message',
        failedMessage: '.fields-mapping-tab .failed-message',
    }

    elements = {}
    customFieldCounter = 0

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.refreshElements();
            this.bindEvents();
            this.updateOrderNumbers();
        });
    }

    refreshElements() {
        this.elements = {
            container: document.querySelector(this.selectors.container),
            fieldsContainer: document.querySelector(this.selectors.fieldsContainer),
            addCustomButton: document.querySelector(this.selectors.addCustomButton),
            customFieldTemplate: document.querySelector(this.selectors.customFieldTemplate),
            saveFieldsButton: document.querySelector(this.selectors.saveFieldsButton),
            successMessage: document.querySelector(this.selectors.successMessage),
            failedMessage: document.querySelector(this.selectors.failedMessage),
        };
    }

    bindEvents() {
        // Add custom field button
        this.elements.addCustomButton?.addEventListener('click', () => {
            this.addCustomField();
        });

        // Delegate click events for order controls, remove button, etc.
        this.elements.fieldsContainer?.addEventListener('click', (e) => {
            // Remove button for custom fields
            const removeBtn = e.target.closest(this.selectors.removeButton);
            if (removeBtn) {
                e.preventDefault();
                const row = removeBtn.closest(this.selectors.fieldRow);
                this.removeField(row);
                return;
            }

            if (e.target.matches(this.selectors.orderUp)) {
                this.moveFieldUp(e.target);
                this.highlightRow(e.target.closest('.field-row'));
            } else if (e.target.matches(this.selectors.orderDown)) {
                this.moveFieldDown(e.target);
                this.highlightRow(e.target.closest('.field-row'));
            }
        });

        // Bind save button event (assumed to have the .save-fields-button class)
        this.elements.saveFieldsButton?.addEventListener('click', () => {
            this.saveFields();
        });
    }

    addCustomField() {
        if (!this.elements.customFieldTemplate) return;

        this.customFieldCounter++;
        const customFieldId = `custom_${Date.now()}_${this.customFieldCounter}`;
        const templateContent = this.elements.customFieldTemplate.innerHTML;

        // Replace template variables
        const newFieldHtml = templateContent
            .replace(/\{\{custom_field_id\}\}/g, customFieldId)
            .replace(/\{\{order\}\}/g, this.getNextOrderNumber())
            .replace(/\{\{label\}\}/g, `Custom Field ${this.customFieldCounter}`)
            .replace(/\{\{value\}\}/g, '');

        const newFieldElement = this.createElementFromHTML(newFieldHtml);
        this.animateFieldEntry(newFieldElement);
        this.elements.fieldsContainer.appendChild(newFieldElement);
        this.updateOrderNumbers();

        const labelInput = newFieldElement.querySelector('input[type="text"]');
        labelInput?.focus();
    }

    removeField(row) {
        if (!row) return;
        row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(-10px)';
        setTimeout(() => {
            row.remove();
            this.updateOrderNumbers();
        }, 300);
    }

    moveFieldUp(upButton) {
        const fieldRow = upButton.closest(this.selectors.fieldRow);
        const previousRow = fieldRow.previousElementSibling;
        if (!previousRow) return; // Already at top
        this.swapFields(fieldRow, previousRow, 'up');
    }

    moveFieldDown(downButton) {
        const fieldRow = downButton.closest(this.selectors.fieldRow);
        const nextRow = fieldRow.nextElementSibling;
        if (!nextRow) return; // Already at bottom
        this.swapFields(fieldRow, nextRow, 'down');
    }

    swapFields(field1, field2, direction) {
        const field1Rect = field1.getBoundingClientRect();
        const field2Rect = field2.getBoundingClientRect();

        // Calculate movement distances
        const field1Distance = field2Rect.top - field1Rect.top;
        const field2Distance = field1Rect.top - field2Rect.top;

        // Initial transform without transition
        field1.style.transform = `translateY(-${field1Distance}px)`;
        field2.style.transform = `translateY(-${field2Distance}px)`;
        field1.style.transition = 'none';
        field2.style.transition = 'none';

        // Force reflow
        field1.offsetHeight;
        field2.offsetHeight;

        // Swap in DOM
        if (direction === 'up') {
            field2.parentNode.insertBefore(field1, field2);
        } else {
            field1.parentNode.insertBefore(field2, field1);
        }

        // Animate the swap
        field1.style.transform = `translateY(-${field1Distance}px)`;
        field2.style.transform = `translateY(-${field2Distance}px)`;

        requestAnimationFrame(() => {
            field1.style.transition = 'all 0.3s linear';
            field2.style.transition = 'all 0.3s linear';

            field1.style.transform = 'translateY(0px)';
            field2.style.transform = 'translateY(0px)';

            this.updateOrderNumbers();

            setTimeout(() => {
                field1.style.transform = '';
                field2.style.transform = '';
                field1.style.transition = '';
                field2.style.transition = '';
                this.updateOrderControlStates();
            }, 300);
        });
    }

    animateFieldEntry(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px) scale(0.95)';
        element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

        requestAnimationFrame(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0px) scale(1)';
            setTimeout(() => {
                element.style.transition = '';
                element.style.transform = '';
            }, 300);
        });
    }

    updateOrderNumbers() {
        const fieldRows = this.elements.fieldsContainer.querySelectorAll(this.selectors.fieldRow);
        fieldRows.forEach((row, index) => {
            const orderNumber = row.querySelector(this.selectors.orderNumber);
            if (orderNumber) {
                orderNumber.textContent = index + 1;
            }
        });
        this.updateOrderControlStates();
    }

    updateOrderControlStates() {
        const fieldRows = this.elements.fieldsContainer.querySelectorAll(this.selectors.fieldRow);
        fieldRows.forEach((row, index) => {
            const upButton = row.querySelector(this.selectors.orderUp);
            const downButton = row.querySelector(this.selectors.orderDown);

            if (index === 0) {
                upButton?.classList.add('cursor-not-allowed', 'opacity-50');
                upButton?.classList.remove('cursor-pointer');
            } else {
                upButton?.classList.remove('cursor-not-allowed', 'opacity-50');
                upButton?.classList.add('cursor-pointer');
            }

            if (index === fieldRows.length - 1) {
                downButton?.classList.add('cursor-not-allowed', 'opacity-50');
                downButton?.classList.remove('cursor-pointer');
            } else {
                downButton?.classList.remove('cursor-not-allowed', 'opacity-50');
                downButton?.classList.add('cursor-pointer');
            }
        });
    }

    getNextOrderNumber() {
        const fieldRows = this.elements.fieldsContainer.querySelectorAll(this.selectors.fieldRow);
        return fieldRows.length + 1;
    }

    createElementFromHTML(htmlString) {
        const div = document.createElement('div');
        div.innerHTML = htmlString.trim();
        return div.firstChild;
    }

    highlightRow(row) {
        this.clearHighlight();
        if (row) {
            row.classList.add(this.selectors.clickedClass);
        }
    }

    clearHighlight() {
        const highlightedRows = this.elements.fieldsContainer?.querySelectorAll(`.${this.selectors.clickedClass}`);
        highlightedRows?.forEach(row => {
            row.classList.remove(this.selectors.clickedClass);
        });
    }

    saveFields() {
        const container = this.elements.container;
        container.classList.add('loading');

        const fieldsData = this.getAllFieldsData();

        $.ajax({
            url: AfzGsObj.ajaxUrl,
            type: 'POST',
            data: {
                action: 'afzaliwp_save_fields',
                form_id: this.elements.container.dataset.formId,
                fields: fieldsData
            },
            dataType: 'json',
            success: (response) => {
                container.classList.remove('loading');
                if (response.success) {
                    this.elements.successMessage.classList.remove('hidden');
                } else {
                    this.elements.failedMessage.classList.remove('hidden');
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                container.classList.remove('loading');
                this.elements.failedMessage.classList.remove('hidden');
            }
        });
    }


    getAllFieldsData() {
        const fields = [];
        const fieldRows = this.elements.fieldsContainer.querySelectorAll(this.selectors.fieldRow);
        fieldRows.forEach((row, index) => {
            const fieldId = row.getAttribute('data-field-id');
            const enabled = row.querySelector('input[type="checkbox"]').checked;
            const order = index + 1;
            let fieldData = {
                id: fieldId,
                enabled: enabled,
                order: order
            };

            const textInputs = row.querySelectorAll('input[type="text"]');
            if (textInputs.length) {
                fieldData.label = textInputs[0].value;
                fieldData.value = textInputs.length > 1 ? textInputs[1].value : '';
            }

            fields.push(fieldData);
        });
        return fields;
    }

}

export default new Fields();
