import { v4 as uuidv4 } from "uuid";

class Gallery {
    static get toolbox() {
        return {
            title: 'Изображение',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[24px] min-h-[24px]" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 8l.01 0"></path>
                        <path d="M4 4m0 3a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3z"></path>
                        <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5"></path>
                        <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2"></path>
                    </svg>`
        };
    }

    static get ICON_DELETE() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M18 6l-12 12"></path>
                   <path d="M6 6l12 12"></path>
                </svg>`;
    }

    static get ICON_DESCRIPTION() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                   <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                   <path d="M9 17h6"></path>
                   <path d="M9 13h6"></path>
                </svg>`;
    }

    static get ORIGIN_URL_MEDIA() {
        return `${window.location.origin}/media/images/`;
    }

    static get LAYOUTS() {
        return ['layout1', 'layout2', 'layout3', 'layout4', 'columns'];
    }

    static get DEFAULT_LAYOUT() {
        return 'layout1';
    }

    get CSS() {
        return {
            defaultGallery: ['grid', 'grid-cols-1', 'gap-4'],
            defaultImage: ['w-full', 'h-full', 'object-cover'],
            defaultImageContainer: ['relative', 'group'],
            emptyBlock: ['flex', 'items-center', 'justify-center', 'gap-1', 'p-4', 'mb-4', 'text-center', 'text-sm', 'text-gray-900', 'rounded', 'border', 'border-gray-300', 'bg-gray-50', 'hover:bg-slate-200', 'transition', 'cursor-pointer', 'select-none', 'outline-none'],
            controlContainer: ['absolute', 'top-2', 'right-2', 'flex', 'items-center', 'gap-2', 'opacity-0', 'group-hover:opacity-100', 'transition'],
            controlBtn: ['flex', 'items-center', 'justify-center', 'p-1', 'rounded', 'text-white', 'bg-gray-600', 'hover:bg-gray-800', 'transition'],
            cleanLayout: ['grid', 'grid-cols-1', 'md:grid-cols-2', 'md:grid-cols-3', 'md:grid-cols-4', 'columns-2', 'space-y-4'],
            layout1: ['grid', 'grid-cols-1'],
            layout2: ['grid', 'md:grid-cols-2'],
            layout3: ['grid', 'md:grid-cols-3'],
            layout4: ['grid', 'md:grid-cols-4'],
            columns: ['columns-2', 'space-y-4'],
            rounded: ['rounded-lg'],
            columnImage: ['!h-auto']
        };
    }

    constructor({data, api}) {
        this.id = uuidv4();

        this._data = this.normalizeData(data);

        /**
         * Editor.js API
         */
        this.api = api

        /**
         * Available Gallery settings
         */
        this.settings = [
            {
                name: 'addImage',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            <path d="M9 12l6 0"></path><path d="M12 9l0 6"></path>
                        </svg>`,
                title: 'Добавить',
            },
            {
                name: 'layout1',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 20v-16l-5 5"></path>
                        </svg>`,
                title: 'Одна колонка',
            },
            {
                name: 'layout2',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 8a4 4 0 1 1 8 0c0 1.098 -.564 2.025 -1.159 2.815l-6.841 9.185h8"></path>
                        </svg>`,
                title: 'Две колонки',
            },
            {
                name: 'layout3',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12a4 4 0 1 0 -4 -4"></path><path d="M8 16a4 4 0 1 0 4 -4"></path>
                        </svg>`,
                title: 'Три колонки',
            },
            {
                name: 'layout4',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 20v-15l-8 11h10"></path>
                        </svg>`,
                title: 'Четыре колонки',
            },
            {
                name: 'columns',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M4 4h6v8h-6z"></path>
                           <path d="M4 16h6v4h-6z"></path>
                           <path d="M14 12h6v8h-6z"></path>
                           <path d="M14 4h6v4h-6z"></path>
                        </svg>`,
                title: 'Гибкие колонки',
            },
            {
                name: 'rounded',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"></path>
                        </svg>`,
                title: 'Закругленные',
            },
        ];

        this.wrapper = '';
        this.empty = '';
    }

    normalizeData(data) {
        const newData = {
            media: data.media ?? [],
            layout: data.layout ?? Gallery.DEFAULT_LAYOUT,
            rounded: data.rounded ?? false,
        };

        return newData;
    }

    render() {
        this.wrapper = this._make('div', ['mb-4'], {
            id: this.id
        });

        this.empty = this._make('div', this.CSS.emptyBlock, {
                id: 'gallery-empty',
                style: 'caret-color: transparent;',
                innerHTML: `
                    ${Gallery.toolbox.icon}
                    Вставьте или нажмите дважды чтобы добавить изображение
                `,
                ondblclick: () => this._toggleTune('addImage'),
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

        this.empty.addEventListener('paste', (event) => { this._eventPasteEmptyBlock(event); }, false);

        this.wrapper.appendChild(this.empty);

        this._generateGallery();
        console.log(this.data)
        return this.wrapper;
    }

    save(blockContent) {
        return this.data;
    }

    validate(savedData) {
        return true;
    }

    /**
     * Sanitizer rules
     */
    static get sanitize() {
        return {
            media: {},
            addImage: {},
            layout: {},
            rounded: {},
        };
    }


    /**
     * Returns images data
     *
     * @returns {ImageGalleryData}
     */
    get data() {
        return this._data;
    }

    /**
     * Set images data and update the view
     *
     * @param {ImageGalleryData} data
     */
    set data(data) {
        this._data = this.normalizeData(data);
    }

    /**
     * Makes buttons with tunes: background mode, layout default, layout horizontal,
     * square layout, layout with gap and layout with fixed size
     *
     * @returns {HTMLDivElement}
     */
    renderSettings() {
        const wrp = this._make('div');

        this.settings.forEach(tune => {
            let button = this._make('div', ['cdx-settings-button'], {
                    id: 'setting-' + tune.name,
                    title: tune.title,
                    innerHTML: tune.icon
                });

            if(this.data.layout === tune.name || this.data[tune.name]) {
                button.classList.add('cdx-settings-button--active');
            }

            wrp.appendChild(button);

            button.addEventListener('click', () => {
                this._toggleTune(tune.name);

                if(Gallery.LAYOUTS.includes(tune.name)) {
                    Gallery.LAYOUTS.forEach(layout => {
                       document.getElementById(`setting-${layout}`).classList.remove('cdx-settings-button--active');
                    });

                    if(this.data.layout === tune.name && !button.classList.contains('cdx-settings-button--active')) {
                        button.classList.add('cdx-settings-button--active');
                    }
                } else {
                    this.data[tune.name] ? button.classList.add('cdx-settings-button--active'): button.classList.remove('cdx-settings-button--active');
                }
            });
        });

        return wrp;
    }

    /**
     * @private
     * Click on the Settings Button
     * @param {string} tune — tune name from this.settings
     */
    _toggleTune(tune) {
        if(tune === 'addImage') {
            this._addImage();
        } else if(Gallery.LAYOUTS.includes(tune)) {
            this.data.layout = tune;
        } else {
            this.data[tune] = !this.data[tune];
        }

        this._acceptTuneView();
    }

    async _addImage() {
        const data = await window.showFileManager('image');

        data.forEach(el => {
            this.data.media.push({
                id: el.id,
                description: el.description
            });
        });

        this._generateGallery();
    }

    /**
     * Add specified logic corresponds with activated tunes
     * @private
     */
    _acceptTuneView() {
        const gallery = this.wrapper.querySelector('#gallery-block');
        const images = gallery ? gallery.querySelectorAll('img') : [];

        if(this.data.media && this.data.media.length && gallery) {
            gallery.classList.remove(...this.CSS.cleanLayout);
            images.forEach(img => img.classList.remove(...this.CSS.columnImage));

            switch (this.data.layout) {
                case 'layout1':
                    gallery.classList.add(...this.CSS.layout1);
                    break;
                case 'layout2':
                    gallery.classList.add(...this.CSS.layout2);
                    break;
                case 'layout3':
                    gallery.classList.add(...this.CSS.layout3);
                    break;
                case 'layout4':
                    gallery.classList.add(...this.CSS.layout4);
                    break;
                case 'columns':
                    gallery.classList.add(...this.CSS.columns);
                    images.forEach(img => img.classList.add(...this.CSS.columnImage));
                    break;
            }

            images.forEach(img => this.data.rounded ? img.classList.add(...this.CSS.rounded) : img.classList.remove(...this.CSS.rounded));
        }
    }

    _generateGallery() {
        const oldBlock = this.wrapper.querySelector('#gallery-block');

        if(oldBlock) {
            oldBlock.remove();
        }

        if(this.data.media && this.data.media.length) {
            this.empty.style.display = 'none';
        } else {
            this.empty.style.display = 'flex';
        }

        const gallery = this._make('div', this.CSS.defaultGallery, {
            id: 'gallery-block'
        });
        
        this.data.media.forEach(media => {
            const imgContainer = this._make('div', this.CSS.defaultImageContainer, {
                id: 'gallery-image'
            });
            const control = this._getControlBtnImage(media.id);

            imgContainer.appendChild(control);

            imgContainer.appendChild(
                this._make('img', this.CSS.defaultImage, {
                    src: Gallery.ORIGIN_URL_MEDIA + media.id
                })
            );

            gallery.appendChild(imgContainer);
        });

        this.wrapper.appendChild(gallery);

        this.api.saver.save();
        this._acceptTuneView();
    }

    _getControlBtnImage(id) {
        const container = this._make('div', this.CSS.controlContainer);

        container.appendChild(
            this._make('button', this.CSS.controlBtn, {
                innerHTML: Gallery.ICON_DESCRIPTION,
                title: 'Изменить описание'
            })
        );

        container.appendChild(
            this._make('button', this.CSS.controlBtn, {
                innerHTML: Gallery.ICON_DELETE,
                title: 'Удалить',
                onclick: (event) => this._eventDeleteImage(event, id)
            })
        );

        return container;
    }

    _eventPasteEmptyBlock(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        const items = (event.clipboardData || event.originalEvent.clipboardData).items;
        navigator.clipboard.writeText('');

        for (let index in items) {
            const item = items[index];

            if (item.kind === 'file' && item.type.startsWith("image/")) {
                new Promise(async resolve => {
                    const uploadFile = await window.uploadFile(item.getAsFile());

                    if(uploadFile) {
                        this.data.media.push({
                            id: uploadFile.id,
                            description: null
                        });
                        this._generateGallery();
                    }
                });
            }
        }
    }

    _eventDeleteImage(event, id) {
        const index = this.data.media.findIndex(media => media.id == id);

        if(index >= 0) {
            const container = event.target.closest('#gallery-image');

            container.remove();
            this.data.media.splice(index, 1);

            if(this.data.media && this.data.media.length === 0) {
                this._generateGallery();
            }
        };
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
}

export { Gallery as default };
