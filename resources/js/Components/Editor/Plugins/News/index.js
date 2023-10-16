import axios from 'axios';

class News {
    constructor({ data, config, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.config = config;

        this._CSS = {
            wrapper: "wdd-news-wrapper",
            defaultConatainer: ['grid', 'grid-cols-4', 'gap-9'],
        };

        if (!this.readOnly) {
            this.onKeyUp = this.onKeyUp.bind(this);
        }

        this._data = this.normalizeData(data);
        this._empty = '';
        this._elements = '';

        this.newsWrapper = undefined;
    }

    normalizeData(data) {
        const newData = {};

        if (typeof data !== 'object') {
            data = {        };
        }

        newData.section = data.section ?? null;

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

    get btnAll() {
        return `
        <div class="flex justify-end mt-6">
            <a class="!no-underline flex group text-lg border-[2px] font-medium text-blue-600 rounded-full pr-6 pl-4 py-2 hover:bg-blue-600 hover:text-white border-blue-600 transition-colors duration-150">
                <svg class="w-7 h-7 mr-2.5 text-blue-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 6l6 6l-6 6"></path>
                </svg>
                Смотреть все
            </a>
        </div>
        `;
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
                title: 'Выбрать',
                type: 'select',
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M19 11v-3a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v5a2 2 0 0 0 2 2h5"></path>
                   <path d="M15.5 15.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0"></path>
                   <path d="M17.5 17.5l2.5 2.5"></path>
                </svg>`,
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
        this.newsWrapper = this._make('div', ['container', 'my-14']);

        this._empty = this._make('div', this.CSS.emptyBlock, {
            id: 'wdd-news-empty',
            style: 'caret-color: transparent;',
            innerHTML: `
                ${News.toolbox.icon}
                Нажмите дважды чтобы выбрать раздел новостей
            `,
            ondblclick: () => this._toggleTune('select'),
            onkeydown: (event) => {
                event.stopImmediatePropagation();
                event.preventDefault();
                return false;
            },
            onfocus: (event) => { event.target.classList.add('border-indigo-600'); },
            onblur: (event) => { event.target.classList.remove('border-indigo-600'); },
        });

        this.newsWrapper.appendChild(this._empty);

        this._renderContainer();

        return this.newsWrapper;
    }

    async _renderContainer() {
        if(this._data.section) {
            this.newsWrapper.querySelector('#wdd-news-empty').style.display = 'none';
        } else {
            this.newsWrapper.querySelector('#wdd-news-empty').style.display = 'flex';

            return null;
        }

        const news = await axios.get('/admin/news/thematic', {
            params: {
                count: 4,
                section: this._data.section
            }
        });

        const section = await axios.get('/admin/thematic-sections/get', {
            params: {
                id: this._data.section
            }
        });

        let oldContainer = this.newsWrapper.querySelector('#wdd-news-container');
        if(oldContainer) { oldContainer.parentNode.removeChild(oldContainer) }

        const newsHeader = this._make('h2', ['font-extrabold', 'text-4xl', 'leading-tight', 'mb-12'])
        newsHeader.innerText = `Новости ${section.name}`;
``
        const newsConatainer = this._make('div', this._CSS.defaultConatainer, {
            id: 'wdd-news-container'
        });

        news.data.forEach(element => {
            const item = this._make('div', ['space-y-5']);

            const itemImage = this._make('img', ['h-[200px]', 'w-full', 'object-cover'], {
                src: '/media/images/' + (element.background_id ?? 'default.webp') + '?h=200'
            });

            const itemTextBody = this._make('div', ['relative', 'space-y-5']);
            const itemText = this._make('p', ['!text-lg', 'font-medium']);
            itemText.innerText = element.title;

            itemTextBody.appendChild(itemText);

            item.appendChild(itemImage);
            item.appendChild(itemTextBody);

            newsConatainer.appendChild(item);
        });

        this.newsWrapper.appendChild(newsHeader);
        this.newsWrapper.appendChild(newsConatainer);
        this.newsWrapper.insertAdjacentHTML( 'beforeend', this.btnAll);
    }

    async save() {
        return {
            section: this._data.section,
        };
    }

    static get toolbox() {
        return {
            icon: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11"></path>
                <path d="M8 8l4 0"></path>
                <path d="M8 12l4 0"></path>
                <path d="M8 16l4 0"></path>
            </svg>`,
            title: 'Новости',
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
        if(type == 'select') {
            const section = await window.selectThematicSection();
            console.log(section)
            if(section) {
                this._data.section = section;
                this._renderContainer();
            }
        } else {
            this._data.type = type;
            this._renderContainer();
        }
    }
}

export { News as default };
