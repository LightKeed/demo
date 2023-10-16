import { v4 as uuidv4 } from "uuid";

class Attachment {
    static get toolbox() {
        return {
            title: 'Документ',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[24px] min-h-[24px]" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                       <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                       <path d="M9 17h6"></path>
                       <path d="M9 13h6"></path>
                    </svg>`
        };
    }

    static get ICON_FILE() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[48px] min-h-[48px]" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                   <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                </svg>`;
    }

    static get ICON_ARCHIVE() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[48px] min-h-[48px]" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M6 20.735a2 2 0 0 1 -1 -1.735v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-1"></path>
                   <path d="M11 17a2 2 0 0 1 2 2v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a2 2 0 0 1 2 -2z"></path>
                   <path d="M11 5l-1 0"></path>
                   <path d="M13 7l-1 0"></path>
                   <path d="M11 9l-1 0"></path>
                   <path d="M13 11l-1 0"></path>
                   <path d="M11 13l-1 0"></path>
                   <path d="M13 15l-1 0"></path>
                </svg>`;
    }

    static get ICON_DELETE() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M18 6l-12 12"></path>
                   <path d="M6 6l12 12"></path>
                </svg>`;
    }

    static get ORIGIN_URL_MEDIA() {
        return `${window.location.origin}/media/`;
    }

    static get DEFAULT_COLUMN() {
        return 1;
    }

    get CSS() {
        return {
            cleanLayout: ['md:grid-cols-2'],
            defaultConatainer: ['grid', 'grid-cols-1', 'gap-4', 'mb-4'],
            defaultAttachmentContainer: ['relative', 'group'],
            defaultAttachmentLink: ['relative', 'w-full', 'flex', 'items-center', 'gap-2', 'text-lg', '!no-underline', 'cursor-pointer'],
            twoColumn: ['md:grid-cols-2'],
            emptyBlock: ['flex', 'items-center', 'justify-center', 'gap-1', 'p-4', 'text-center', 'text-sm', 'text-gray-900', 'rounded', 'border', 'border-gray-300', 'bg-gray-50', 'hover:bg-slate-200', 'transition', 'cursor-pointer', 'select-none', 'outline-none'],
            controlBtn: ['absolute', 'top-2', 'right-2', 'opacity-0', 'group-hover:opacity-100', 'transition'],
            iconContainer: ['relative', 'text-blue-600', 'font-bold', 'select-none'],
            iconExtension: ['absolute', 'bottom-[-5px]', 'left-0', 'p-[2px]', 'bg-white', 'leading-none', 'text-base'],
            link: ['!no-underline', 'group-hover:!underline']
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
                name: 'add',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            <path d="M9 12l6 0"></path><path d="M12 9l0 6"></path>
                        </svg>`,
                title: 'Добавить',
            },
            {
                name: 'oneColumn',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M5 3m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z"></path>
                        </svg>`,
                title: 'Одна колонка',
                value: 1
            },
            {
                name: 'twoColumn',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M3 3m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1zm9 -1v18"></path>
                        </svg>`,
                title: 'Две колонки',
                value: 2
            },
        ];

        this.wrapper = '';
        this.empty = '';
    }

    normalizeData(data) {
        const newData = {};

        newData.items = data.items || [];
        newData.columns = data.columns || Attachment.DEFAULT_COLUMN

        return newData;
    }

    render() {
        this.wrapper = this._make('div', ['mb-4'], {
            id: this.id
        });

        this.empty = this._make('div', this.CSS.emptyBlock, {
            id: 'attachment-empty',
            style: 'caret-color: transparent;',
            innerHTML: `
                ${Attachment.toolbox.icon}
                Вставьте или нажмите дважды чтобы добавить документ
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

        this.empty.addEventListener('paste', (event) => { this._eventPasteEmptyBlock(event); }, false);

        this.wrapper.appendChild(this.empty);

        //if (this.data.items.length) {
        this._generateContainer();
        //return this.wrapper;
        //}

        console.log(this.wrapper);

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
            items: {},
            columns: {}
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
        //console.log(data)
        //this._data = Object.assign({}, this.data, data);
    }

    /**
     * Makes buttons with tunes: background mode, layout default, layout horizontal,
     * square layout, layout with gap and layout with fixed size
     *
     * @returns {HTMLDivElement}
     */
    renderSettings() {
        const wrapper = this._make('div');

        this.settings.forEach(tune => {
            let button = this._make('div', ['cdx-settings-button'], {
                    id: 'setting-' + tune.name,
                    title: tune.title,
                    innerHTML: tune.icon
                });

            button.addEventListener('click', () => {
                this._toggleTune(tune.name);
            });

            wrapper.appendChild(button);
        });

        return wrapper;
    }

    /**
     * @private
     * Click on the Settings Button
     * @param {string} tune — tune name from this.settings
     */
    _toggleTune(tune) {
        if(tune === 'add') {
            this._add();
        } else if(tune === 'oneColumn') {
            this.data.columns = 1;
        } else if(tune === 'twoColumn') {
            this.data.columns = 2;
        }

        this._acceptTuneView();
    }

    async _add() {
        const data = await window.showFileManager(['document', 'archive', 'table']);

        data.forEach(el => {
            this.data.items.push({
                id: el.id,
                fullname: el.fullname,
                path: el.path,
                type: el.type,
                extension: el.extension
            });
        });

        this._generateContainer();
    }

    /**
     * Add specified logic corresponds with activated tunes
     * @private
     */
    _acceptTuneView() {
        //const container = document.getElementById(this.id).querySelector('#attachment-block');
        const attachments = this.wrapper.querySelector('#attachment-block').querySelectorAll('#attachment') || [];

        if(this.data.items && this.data.items.length && this.wrapper) {
            this.wrapper.querySelector('#attachment-block').classList.remove(...this.CSS.cleanLayout);

            if(this.data.columns === 2) {
                this.wrapper.querySelector('#attachment-block').classList.add(...this.CSS.twoColumn);
            }
        }
    }

    _generateContainer() {
        //const container = document.getElementById(this.id);
        const oldBlock = this.wrapper.querySelector('#attachment-block');

        if(oldBlock) {
            oldBlock.remove();
        }

        if(this.data.items && this.data.items.length) {
            this.wrapper.querySelector('#attachment-empty').style.display = 'none';
        } else {
            this.wrapper.querySelector('#attachment-empty').style.display = 'flex';
        }

        const attachmentConatainer = this._make('div', this.CSS.defaultConatainer, {
            id: 'attachment-block'
        });

        this.data.items.forEach(media => {
            const attach = this._make('div', this.CSS.defaultAttachmentContainer, {
                id: 'attachment-item'
            });

            const link = this._make('a', this.CSS.defaultAttachmentLink, {
                href: Attachment.ORIGIN_URL_MEDIA + media.path,
                target: '_blank'
            })

            const icon = this._getFileIcon(media.extension, media.type);
            const title = this._getFileTitle(media.fullname);
            const control = this._getControlBtn(media.id);

            link.appendChild(icon);
            link.appendChild(title);
            attach.appendChild(link);
            attach.appendChild(control);

            attachmentConatainer.appendChild(attach);
        });

        this.wrapper.appendChild(attachmentConatainer);

        this.api.saver.save();
        this._acceptTuneView();
    }

    _getFileIcon(extension, type) {
        const icon = this._make('div', this.CSS.iconContainer, {
            innerHTML: type === 'archive' ? Attachment.ICON_ARCHIVE : Attachment.ICON_FILE
        });

        if(type !== 'archive') {
            icon.appendChild(this._make('span', this.CSS.iconExtension, {
                innerText: extension
            }));
        }

        return icon;
    }

    _getFileTitle(title, path) {
        const link = this._make('span', this.CSS.link, {
            innerText: title
        });

        return link;
    }

    _getControlBtn(id) {
        const btn = this._make('button', this.CSS.controlBtn, {
            innerHTML: Attachment.ICON_DELETE,
            title: 'Удалить',
            onclick: (event) => this._eventDeleteAttach(event, id)
        });

        return btn;
    }

    _eventPasteEmptyBlock(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        const items = (event.clipboardData || event.originalEvent.clipboardData).items;
        navigator.clipboard.writeText('');

        for (let index in items) {
            const item = items[index];

            if (item.kind === 'file') {
                new Promise(async resolve => {
                    const uploadFile = await window.uploadFile(item.getAsFile(), ['document', 'table', 'archive']);

                    if(uploadFile) {
                        this.data.items.push({
                            id: uploadFile.id,
                            fullname: uploadFile.fullname,
                            path: uploadFile.path,
                            type: uploadFile.type,
                            extension: uploadFile.extension
                        });
                        this._generateContainer();
                    }
                });
            }
        }
    }

    _eventDeleteAttach(event, id) {
        const index = this.data.items.findIndex(attach => attach.id == id);

        if(index >= 0) {
            const container = event.target.closest('#attachment-item');

            container.remove();
            this.data.items.splice(index, 1);

            if(this.data.items && this.data.items.length === 0) {
                this._generateContainer();
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

export { Attachment as default };
