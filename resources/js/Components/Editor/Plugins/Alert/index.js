import style1 from './style.css?inline'

export default class Alert {
    static get toolbox() {
        return {
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
                       <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                    </svg>`,
            title: 'Уведомление',
        };
    }

    static get enableLineBreaks() {
        return true;
    }

    static get DEFAULT_TYPE() {
        return 'info';
    }

    static get DEFAULT_MESSAGE_PLACEHOLDER() {
        return 'Введите уведомление...';
    }

    static get ALERT_TYPES() {
        return [
            'info',
            'secondary',
            'success',
            'warning',
            'danger',
            'light',
            'dark',
        ];
    }

    static get ALERT_ICONS() {
        return {
            'info': `<svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                       <path d="M12 8l.01 0"></path>
                       <path d="M11 12l1 0l0 4l1 0"></path>
                    </svg>`,
            'secondary': `<svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M12 8l.01 0"></path>
                           <path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>
                           <path d="M11 12l1 0l0 4l1 0"></path>
                        </svg>`,
            'success': `<svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                           <path d="M9 12l2 2l4 -4"></path>
                        </svg>`,
            'warning': `<svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M12 9v2m0 4v.01"></path>
                           <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
                        </svg>`,
            'danger': `<svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                           <path d="M12 8l0 4"></path>
                           <path d="M12 16l.01 0"></path>
                        </svg>`
        };
    }

    get CSS() {
        return {
            alert: ['flex', 'gap-2', 'border', 'rounded-lg', 'p-2.5', 'pr-8', 'relative', 'mb-4'],
            info: ['text-blue-800', 'border-blue-300', 'bg-blue-50'],
            secondary: ['text-slate-800', 'border-slate-300', 'bg-slate-200'],
            success: ['text-green-800', 'border-green-300', 'bg-green-50'],
            warning: ['text-yellow-800', 'border-yellow-300', 'bg-yellow-50'],
            danger: ['text-red-800', 'border-red-300', 'bg-red-50'],
            light: ['text-gray-800', 'border-gray-300', 'bg-gray-50'],
            dark: ['text-gray-300', 'border-gray-600', 'bg-gray-800'],
            settingBtn: ['border', 'rounded', 'opacity-50', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center', 'hover:opacity-100', 'cursor-pointer', 'select-none'],
            settingActive: ['font-bold', 'border-indigo-600', '!opacity-100'],
            closeBtn: ['absolute', 'top-2', 'right-2', 'cursor-pointer', 'select-none']
        };
    }

    constructor({ data, config, api, readOnly }) {
        this.api = api;

        this.defaultType = config.defaultType || Alert.DEFAULT_TYPE;
        this.messagePlaceholder = config.messagePlaceholder || Alert.DEFAULT_MESSAGE_PLACEHOLDER;

        this.data = {
            type: Alert.ALERT_TYPES.includes(data.type)
                ? data.type
                : this.defaultType,
            message: data.message || '',
        };

        this.container = undefined;

        this.readOnly = readOnly;
    }

    static get isReadOnlySupported() {
        return true;
    }

    render() {
        const containerClasses = [
            ...this.CSS.alert,
            ...this._getAlertStyles(this.data.type)
        ];
        const closeBtn = this._getCloseBtn();
        const icon = this._getAlertIcon(this.data.type);

        this.container = this._make('div', containerClasses, {
            innerHTML: icon
        });

        const messageEl = this._make('div', ['cdx-block', 'w-full', 'h-full', '!p-0', 'outline-none'], {
            id: 'alert-content',
            contentEditable: !this.readOnly,
            innerHTML: this.data.message,
        });

        messageEl.dataset.placeholder = this.messagePlaceholder;

        this.container.appendChild(messageEl);
        this.container.appendChild(closeBtn);

        return this.container;
    }

    renderSettings() {
        const settingsContainer = this._make('div', ['flex', 'gap-1']);

        Alert.ALERT_TYPES.forEach((type) => {
            const settingsButton = this._make(
                'div',
                [
                    ...this.CSS.settingBtn,
                    ...this._getAlertStyles(type),
                ],
                {
                    id: 'setting-alert',
                    innerHTML: 'A',
                }
            );

            if (this.data.type === type) {
                // Highlight current type button
                settingsButton.classList.add(...this.CSS.settingActive);
            }

            // Set up click handler
            settingsButton.addEventListener('click', () => {
                this._changeAlertType(type);

                // Un-highlight previous type button
                settingsContainer
                    .querySelectorAll(`#setting-alert`)
                    .forEach((button) =>
                        button.classList.remove(...this.CSS.settingActive)
                    );

                // and highlight the clicked type button
                settingsButton.classList.add(...this.CSS.settingActive);
            });

            settingsContainer.appendChild(settingsButton);
        });

        return settingsContainer;
    }

    _changeAlertType(newType) {
        if(newType !== this.data.type) {
            const oldType = this.data.type;
            const icon = this._getAlertIcon(newType);

            this.data.type = newType;

            this.container.querySelector('.icon').remove();
            this.container.innerHTML = icon + this.container.innerHTML;

            this.container.classList.remove(...this._getAlertStyles(oldType));
            this.container.classList.add(...this._getAlertStyles(newType));
        }
    }

    /**
     * Extract Alert data from Alert Tool element
     *
     * @param {HTMLDivElement} alertElement - element to save
     * @returns {AlertData}
     */
    save(alertElement) {
        const messageEl = alertElement.querySelector('#alert-content');

        console.log('edit');

        return { ...this.data, message: messageEl.innerHTML };
    }

    _make(tagName, classNames = null, attributes = {}) {
        let el = document.createElement(tagName);

        if (Array.isArray(classNames)) {
            el.classList.add(...classNames);
        } else if (classNames) {
            el.classList.add(classNames);
        }

        for (let attrName in attributes) {
            el[attrName] = attributes[attrName];
        }

        return el;
    }

    _getAlertStyles(type) {
        return this.CSS[type] ?? [];
    }

    _getAlertIcon(type) {
        return `<div class="icon">${Alert.ALERT_ICONS[type] ?? ''}</div>`;
    }

    _getCloseBtn() {
        const btn = this._make('div', this.CSS.closeBtn, {
            innerHTML: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M18 6l-12 12"></path>
                           <path d="M6 6l12 12"></path>
                        </svg>`
        });

        return btn;
    }

    /**
     * Fill Alert's message with the pasted content
     *
     * @param {PasteEvent} event - event with pasted content
     */
    onPaste(event) {
        const { data } = event.detail;

        this.data = {
            type: this.defaultType,
            message: data.innerHTML || '',
        };
    }

    /**
     * Sanitizer config for Alert Tool saved data
     * @returns {Object}
     */
    static get sanitize() {
        return {
            type: false,
            message: true,
        };
    }
}