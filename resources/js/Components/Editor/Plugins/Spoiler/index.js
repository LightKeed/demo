import { v4 as uuidv4 } from "uuid";

import style from './index.css?inline'
import { IconH3, IconH4 } from '@codexteam/icons';
import EditorJS from '@editorjs/editorjs'; // required for npm mode

class Spoiler {
    constructor({ data, config, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.config = config;

        this._CSS = {
            block: this.api.styles.block,
            wrapper: "wdd-wrapper",
        };

        if (!this.readOnly) {
            this.onKeyUp = this.onKeyUp.bind(this);
        }

        this._data = this.normalizeData(data);
        this._element = '';
        this._isOpen = true;

        this.editor = {};
        this.colWrapper = undefined;
    }

    normalizeData(data) {
        const newData = {};

        if (typeof data !== 'object') {
            data = {};
        }

        newData.title = data.title || {};
        newData.title.text = data.title ? data.title.text : 'Название спойлера';
        newData.title.level = data.title ? data.title.level : 3;
        newData.col = data.col || '';

        //console.log(newData);

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
            input: this.api.styles.input,
            title: 'wdd-spoiler-title',
        };
    }

    get data() {
        this._data.title.text = this._element.innerHTML;
        this._data.title.level = this.currentLevel.number;

        return this._data;
    }

    set data(data) {
        this._data = this.normalizeData(data);

        //if (data.title.level !== undefined && this._element.parentNode) {
        const newHeader = this._make(this.currentLevel.tag, ['wdd-spoiler-heading'], {
            contentEditable: !this.readOnly,
            innerHTML: this.data.title.text
        });

        this._element.parentNode.replaceChild(newHeader, this._element);
        this._element = newHeader;
    }

    get levels() {
        const availableLevels = [
            {
                number: 3,
                tag: 'H3',
                svg: IconH3,
            },
            {
                number: 4,
                tag: 'H4',
                svg: IconH4,
            },
        ];

        return availableLevels;
    }

    get currentLevel() {
        let level = this.levels.find(levelItem => levelItem.number === this._data.title.level);

        return level;
    }

    renderSettings() {
        return this.levels.map(level => ({
            icon: level.svg,
            label: this.api.i18n.t(`Heading ${level.number}`),
            onActivate: () => this._toggleTune(level.number),
            closeOnActivate: true,
            isActive: this.data.title.level === level.number,
        }));
    }

    _toggleTune(level) {
        this.data = {
            title: {
                text: this.data.title.text,
                level: level,
            },
            col: this.data.col
        }
    }

    render() {
        let editor_col_id = uuidv4();

        this.colWrapper = this._make('div', ['wdd-spoiler-wrapper'], null);

        const col = this._make('div', ['wdd-spoiler'], {
            id: editor_col_id
        });

        this._element = this._make(this.currentLevel.tag, ['wdd-spoiler-heading'], {
            contentEditable: !this.readOnly,
            innerHTML: this._data.title.text
        });

        let header = this._make('div', ['wdd-spoiler-header', 'flex', 'items-center', 'flex-row-reverse', 'place-content-start'], null);
        header.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" class="wdd-spoiler-button h-8 w-8 transition ease-in-out delay-50 cursor-pointer ml-4 flex-none rotate-180"\n' +
            '             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"\n' +
            '             stroke-linejoin="round">\n' +
            '            <path d="M6 9l6 6l6 -6"></path>\n' +
            '        </svg>';

        header.appendChild(this._element);
        this.colWrapper.appendChild(header);
        this.colWrapper.appendChild(col);

        header.querySelector('svg').addEventListener("click", (event) => {
            this._isOpen = !this._isOpen;
            if(!this._isOpen) {
                this.colWrapper.querySelector('.wdd-spoiler').classList.add('hidden')
                this.colWrapper.querySelector('.wdd-spoiler-header svg').classList.remove('rotate-180')
            } else {
                this.colWrapper.querySelector('.wdd-spoiler').classList.remove('hidden')
                this.colWrapper.querySelector('.wdd-spoiler-header svg').classList.add('rotate-180')
            }
        });

        let editorjs_instance = new EditorJS({
            defaultBlock: "paragraph",
            holder: editor_col_id,
            tools: this.config.tools,
            data: this._data.col,
            readOnly: this.readOnly,
            minHeight: 50,
            onReady: (event) => {
                this._stopPasteEvent();
                col.addEventListener('keydown', event => {
                    if (event.keyCode == 9) {
                        col.querySelector('.ce-toolbar__plus').click();
                        event.stopImmediatePropagation();
                    }
                }, true);
            },
            onChange: (event) => {
                this._stopPasteEvent();
            }
        });

        this.editor = editorjs_instance;

        return this.colWrapper;
    }

    _stopPasteEvent() {
        this.colWrapper.querySelectorAll('.cdx-block').forEach(block => {
            block.addEventListener('paste', event => {
                event.stopImmediatePropagation();
            }, true);
        });
    }

    async save(blockContent) {
        let col = await this.editor.save();
        //this.editor.isReady.then(async () => { })
        let text = blockContent.querySelector('.wdd-spoiler-heading').innerHTML;
        text = text.replace("&nbsp;", "");

        return {
            title: {
                text: text,
                level: this.currentLevel.number,
            },
            col: col
        };
    }

    static get toolbox() {
        return {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-toggle-horizontal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                '   <path d="M22 12h-20"></path>\n' +
                '   <path d="M4 14v-8a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v8"></path>\n' +
                '   <path d="M18 20a2 2 0 0 0 2 -2"></path>\n' +
                '   <path d="M4 18a2 2 0 0 0 2 2"></path>\n' +
                '   <path d="M14 20l-4 0"></path>\n' +
                '</svg>',
            title: 'Спойлер',
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
}

export { Spoiler as default };
