class Tabs {
    selectors = {
        tabsContainer: '.afzaliwp-gs-wrapper .tabs',
        tabButtons: '.afzaliwp-gs-wrapper .tab-button',
        contentContainer: '.afzaliwp-gs-wrapper .contents',
        contentItems: '.afzaliwp-gs-wrapper .content'
    }

    elements = {
        tabButtons: null,
        contentItems: null
    }

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.refreshElements();
            this.handleTabs();
            this.activateInitialTab();
        });
    }

    refreshElements() {
        this.elements.tabButtons = document.querySelectorAll(this.selectors.tabButtons);
        this.elements.contentItems = document.querySelectorAll(this.selectors.contentItems);
    }

    handleTabs() {
        const {tabButtons} = this.elements;

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.dataset.tab;
                localStorage.setItem(this.getStorageKey(), tabName);

                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                this.showContent(tabName);
            });
        });
    }

    getFormIdFromURL() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id') || 'default';
    }

    getStorageKey() {
        const formId = this.getFormIdFromURL();
        return `afzaliwp_gs_active_tab_form_${formId}`;
    }


    activateInitialTab() {
        const savedTab = localStorage.getItem(this.getStorageKey());
        if (savedTab) {
            const targetButton = document.querySelector(`${this.selectors.tabButtons}[data-tab="${savedTab}"]`);
            if (targetButton) {
                this.elements.tabButtons.forEach(btn => btn.classList.remove('active'));
                targetButton.classList.add('active');
                this.showContent(savedTab);
                return;
            }
        }

        const firstActive = document.querySelector(`${this.selectors.tabButtons}.active`);
        if (firstActive) {
            this.showContent(firstActive.dataset.tab);
        }
    }


    showContent(tabName) {
        const {contentItems} = this.elements;

        contentItems.forEach(content => {
            if (content.dataset.content === tabName) {
                content.classList.add('active');
                content.style.display = 'block';
            } else {
                content.classList.remove('active');
                content.style.display = 'none';
            }
        });
    }
}

export default new Tabs();