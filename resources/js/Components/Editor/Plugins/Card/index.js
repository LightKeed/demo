import axios from 'axios';
import './style.css';

class Card {
    constructor({ data, config, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.config = config;

        this._CSS = {
            wrapper: "wdd-card-wrapper",
            defaultConatainer: ['grid', 'grid-cols-1', 'gap-4', 'mb-4'],
        };

        if (!this.readOnly) {
            this.onKeyUp = this.onKeyUp.bind(this);
        }

        this._data = this.normalizeData(data);
        this._empty = '';
        this._elements = '';

        this.cardWrapper = undefined;
    }

    normalizeData(data) {
        const newData = {};

        if (typeof data !== 'object') {
            data = {};
        }

        newData.type = data.type || 1;
        newData.ids = data.ids || {};

        return newData;
    }

    static get isReadOnlySupported() {
        return true;
    }

    onKeyUp(e) {
        if (e.code !== "Backspace" && e.code !== "Delete") {
            return;
        }
    }

    get CSS() {
        return {
            settingsButton: this.api.styles.settingsButton,
            settingsButtonActive: this.api.styles.settingsButtonActive,
            emptyBlock: ['flex', 'items-center', 'justify-center', 'gap-1', 'p-4', 'text-center', 'text-sm', 'text-gray-900', 'rounded', 'border', 'border-gray-300', 'bg-gray-50', 'hover:bg-slate-200', 'transition', 'cursor-pointer', 'select-none', 'outline-none'],
            input: this.api.styles.input,
            title: 'wdd-spoiler-title',
        };
    }

    get data() {
        return this._data;
    }

    set data(data) {
        this._data = this.normalizeData(data);
    }

    get settings() {
        const availableSettings = [
            {
                title: 'Добавить',
                type: 'add',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M12 5l0 14"></path>\n' +
                    '   <path d="M5 12l14 0"></path>\n' +
                    '</svg>',
            },
            {
                title: 'Подразделение',
                type: 1,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" class="" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M3 21l18 0"></path>\n' +
                    '   <path d="M9 8l1 0"></path>\n' +
                    '   <path d="M9 12l1 0"></path>\n' +
                    '   <path d="M9 16l1 0"></path>\n' +
                    '   <path d="M14 8l1 0"></path>\n' +
                    '   <path d="M14 12l1 0"></path>\n' +
                    '   <path d="M14 16l1 0"></path>\n' +
                    '   <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"></path>\n' +
                    '</svg>',
            },
            {
                title: 'Должность',
                type: 2,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chef-hat" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M12 3c1.918 0 3.52 1.35 3.91 3.151a4 4 0 0 1 2.09 7.723l0 7.126h-12v-7.126a4 4 0 1 1 2.092 -7.723a4 4 0 0 1 3.908 -3.151z"></path>\n' +
                    '   <path d="M6.161 17.009l11.839 -.009"></path>\n' +
                    '</svg>',
            },
            {
                title: 'Человек',
                type: 3,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>\n' +
                    '   <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>\n' +
                    '</svg>',
            }
        ];

        return availableSettings;
    }

    renderSettings() {
        return this.settings.map(setting => ({
            icon: setting.svg,
            label: setting.title,
            onActivate: () => this._toggleTune(setting.type),
            closeOnActivate: true,
            isActive: this._data.type === setting.type,
        }));
    }

    render() {
        this.cardWrapper = this._make('div', ['wdd-card-wrapper'], null);

        this._empty = this._make('div', this.CSS.emptyBlock, {
            id: 'wdd-card-empty',
            style: 'caret-color: transparent;',
            innerHTML: `
                ${Card.toolbox.icon}
                Нажмите дважды чтобы добавить карточку
            `,
            ondblclick: () => this._toggleTune('add'),
            onkeydown: (event) => {
                event.stopImmediatePropagation();

                if((event.ctrlKey !== true && event.key !== 'v') || event.repeat) {
                    event.preventDefault();
                    return false;
                }
            },
            onfocus: (event) => { event.target.classList.add('border-indigo-600'); },
            onblur: (event) => { event.target.classList.remove('border-indigo-600'); },
            contentEditable: true
        });

        this.cardWrapper.appendChild(this._empty);

        this._renderContainer();

        return this.cardWrapper;
    }

    async _renderContainer() {
        if(this._data.ids && this._data.ids.length) {
            this.cardWrapper.querySelector('#wdd-card-empty').style.display = 'none';
        } else {
            this.cardWrapper.querySelector('#wdd-card-empty').style.display = 'flex';

            return null;
        }

        const cards = await axios.get('/admin/employees/card', {
            params: {
                ids: this._data.ids,
                type: this._data.type
            }
        })

        let oldContainer = this.cardWrapper.querySelector('#wdd-card-container');
        if(oldContainer) { oldContainer.parentNode.removeChild(oldContainer) }

        const cardConatainer = this._make('div', this.CSS.defaultConatainer, {
            id: 'wdd-card-container'
        });

        cards.data.forEach(element => {
            const card = this._make('div', ['wdd-card']);

            const cardPhoto = this._make('img', ['wdd-card-photo'], {
                src: '/media/images/' + (element.photo_id ?? 'default.webp') + '?w=210&h=305&fit=crop'
            });

            card.appendChild(cardPhoto);

            const cardInfo = this._make('div', ['wdd-card-info']);
            const head = this._make('div', ['head']);

            const title = this._make('h4', ['title'], {
                innerHTML: element.title
            });

            const subtitle = this._make('p', ['subtitle'], {
                innerHTML: element.subtitle
            });

            head.appendChild(title);
            head.appendChild(subtitle);

            const addressCard = this._make('div', ['address']);

            if(element.address) {
                const address = this._make('div', ['address'], {
                    innerHTML: `<svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                          </svg>`
                });

                let text = this._make('div', null, {
                    innerHTML: element.address
                });

                address.appendChild(text);

                addressCard.appendChild(address);
            }

            const contacts = this._make('div', ['contacts']);

            if(element.phone) {
                if(typeof JSON.parse(element.phone) === 'object') {
                    JSON.parse(element.phone).forEach(element => {
                        const contact = this._make('div', ['contact'], {
                            innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                                '   <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>\n' +
                                '</svg>'
                        });

                        let text = this._make('div', null, {
                            innerHTML: element
                        });

                        contact.appendChild(text);

                        contacts.appendChild(contact);
                    })
                } else {
                    const contact = this._make('div', ['contact'], {
                        innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                            '   <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>\n' +
                            '</svg>'
                    });

                    let text = this._make('div', null, {
                        innerHTML: JSON.parse(element.phone)
                    });

                    contact.appendChild(text);

                    contacts.appendChild(contact);
                }

            }

            const email = this._make('div', ['email']);

            if(element.email) {
                if(typeof JSON.parse(element.email) === 'object') {
                    JSON.parse(element.email).forEach(element => {
                        const mail = this._make('div', ['mail'], {
                            innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                                '   <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>\n' +
                                '   <path d="M3 7l9 6l9 -6"></path>\n' +
                                '</svg>'
                        });

                        let text = this._make('div', null, {
                            innerHTML: element
                        });

                        mail.appendChild(text);

                        email.appendChild(mail);
                    })
                } else {
                    const mail = this._make('div', ['mail'], {
                        innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                            '   <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>\n' +
                            '   <path d="M3 7l9 6l9 -6"></path>\n' +
                            '</svg>'
                    });

                    let text = this._make('div', null, {
                        innerHTML: JSON.parse(element.email)
                    });

                    mail.appendChild(text);

                    email.appendChild(mail);
                }
            }

            cardInfo.appendChild(head);
            cardInfo.appendChild(addressCard);
            cardInfo.appendChild(contacts);
            cardInfo.appendChild(email);

            card.appendChild(cardInfo);

            cardConatainer.appendChild(card);
        });

        this.cardWrapper.appendChild(cardConatainer);
    }

    async save() {
        return {
            ids: this._data.ids,
            type: this._data.type
        };
    }

    static get toolbox() {
        return {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                '   <path d="M7 12h3v4h-3z"></path>\n' +
                '   <path d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6"></path>\n' +
                '   <path d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z"></path>\n' +
                '   <path d="M14 16h2"></path>\n' +
                '   <path d="M14 12h4"></path>\n' +
                '</svg>',
            title: 'Карточки',
        };
    }

    _make(tagName, classNames = null, attributes = {}) {
        const el = document.createElement(tagName);

        if (Array.isArray(classNames)) {
            el.classList.add(...classNames);
        } else if (classNames) {
            el.classList.add(classNames);
        }

        for (const attrName in attributes) {
            el[attrName] = attributes[attrName];
        }

        return el;
    }

    async _toggleTune(type) {
        if(type == 'add') {
            let ids = [];
            let _ids = await window.showPersonalCard();
            if(_ids.length > 0) {
                _ids.forEach(element => {
                    ids.push(element.id);
                })
                this._data.ids = ids;
                this._renderContainer();
            }
        } else {
            this._data.type = type;
            this._renderContainer();
        }
    }
}

export { Card as default };
