/**
 * Column Block for the Editor.js.
 *
 * @author Calum Knott (calum@calumk.com)
 * @copyright Calum Knott
 * @license The MIT License (MIT)
 */

/**
 * @typedef {Object} EditorJsColumnsData
 * @description Tool's input and output data format
 */

import { v4 as uuidv4 } from "uuid";

import style from './style.css?inline'

import EditorJS from '@editorjs/editorjs'; // required for npm mode

class EditorJsColumns {
    constructor({ data, config, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.config = config || {};

        this._CSS = {
            block: this.api.styles.block,
            wrapper: "ce-EditorJsColumns",
        };

        this.editors = {};

        this.colWrapper = undefined;

        this.editors.cols = [];

        console.log(data);

        this._data = this.normalizeData(data);
    }

    normalizeData(data) {
        const newData = {};

        if (typeof data !== 'object') {
            data = {};
        }

        newData.block_ratio = data.block_ratio || 1;
        newData.cols = data.cols || '';

        if (!Array.isArray(data.cols)) {
            newData.cols = [];
            this.editors.numberOfColumns = 2;
        } else {
            this.editors.numberOfColumns = newData.cols.length;
        }

        return newData;
    }

    get data() {
        return this._data;
    }

    set data(data) {
        this._data = this.normalizeData(data);
    }

    static get isReadOnlySupported() {
        return true;
    }

    get CSS() {
        return {
            settingsButton: this.api.styles.settingsButton,
            settingsButtonActive: this.api.styles.settingsButtonActive,
        };
    }

    async _rerender() {
        await this.save();

        for (let index = 0; index < this.editors.cols.length; index++) {
            this.editors.cols[index].destroy();
        }

        this.editors.cols = [];

        this.colWrapper.innerHTML = "";

        for (let index = 0; index < this.editors.numberOfColumns; index++) {
            // console.log("Start column, ", index);
            let col = document.createElement("div");
            col.classList.add("ce-editorjsColumns_col");
            col.classList.add("editorjs_col_" + index);

            let editor_col_id = uuidv4();

            col.id = editor_col_id;

            this.colWrapper.appendChild(col);

            let editorjs_instance = new EditorJS({
                defaultBlock: "paragraph",
                holder: editor_col_id,
                tools: this.config.tools,
                data: this._data.cols[index],
                readOnly: this.readOnly,
                minHeight: 50,
            });

            this.editors.cols.push(editorjs_instance);
        }

        this.colWrapper.classList.remove("grid-cols-1");
        this.colWrapper.classList.remove("grid-cols-2");
        this.colWrapper.classList.remove("grid-cols-3");

        if(this.editors.cols.length == 2) {
            this.colWrapper.classList.add("grid-cols-2");
        } else if(this.editors.cols.length == 3) {
            this.colWrapper.classList.add("grid-cols-3");
        } else {
            this.colWrapper.classList.add("grid-cols-1");
        }
    }

    render() {
        this.colWrapper = document.createElement("div");

        this.colWrapper.classList.add("ce-editorjsColumns_wrapper");
        this.colWrapper.classList.add("grid");
        this.colWrapper.classList.add("gap-4");

        for (let index = 0; index < this.editors.cols.length; index++) {
            this.editors.cols[index].destroy();
        }

        this.editors.cols = [];

        console.log(this._data);

        for (let index = 0; index < this.editors.numberOfColumns; index++) {
            let col = document.createElement("div");
            col.classList.add("ce-editorjsColumns_col");
            col.classList.add("editorjs_col_" + index);

            let editor_col_id = uuidv4();
            col.id = editor_col_id;

            col = this.colWrapper.appendChild(col);

            let editorjs_instance = new EditorJS({
                defaultBlock: "paragraph",
                logLevel : 'ERROR',
                holder: editor_col_id,
                tools: this.config.tools,
                data: this._data.cols[index],
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

            this.editors.cols.push(editorjs_instance);
        }

        if(this.editors.cols.length == 2) {
            this.colWrapper.classList.add("grid-cols-2");
        } else if(this.editors.cols.length == 3) {
            this.colWrapper.classList.add("grid-cols-3");
        } else {
            this.colWrapper.classList.add("grid-cols-1");
        }

        return this.colWrapper;
    }

    _stopPasteEvent() {
        this.colWrapper.querySelectorAll('.cdx-block').forEach(block => {
            block.addEventListener('paste', event => {
                event.stopImmediatePropagation();
            }, true);
        });
    }

    async save() {
        for (let index = 0; index < this.editors.cols.length; index++) {
            let colData = await this.editors.cols[index].save();
            this._data.cols[index] = colData;
        }

        return {
            block_ratio: this._data.block_ratio,
            cols: this._data.cols
        };
    }

    async _updateCols(num) {
        if (num == 1) {
            this.editors.numberOfColumns = 1;
            this._data.cols.pop();
            this.editors.cols.pop();
            this._rerender();
        }
        if (num == 2) {
            if (this.editors.numberOfColumns == 3) {
                this.editors.numberOfColumns = 2;
                this._data.cols.pop();
                this.editors.cols.pop();
                this._rerender();
            } else {
                this.editors.numberOfColumns = 2;
                this._rerender();
            }
        }
        if (num == 3) {
            this.editors.numberOfColumns = 3;
            this._rerender();
        }
        if (num == 90) {
            this._data.cols.unshift(this._data.cols.pop());
            this.editors.cols.unshift(this.editors.cols.pop());
            this._rerender();
        } else if (num == 91) {
            this._rerender();
        }
    }

    get settings() {
        const availableSettings = [
            {
                title: '1 Колонка',
                number: 1,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M5 3m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z"></path>\n' +
                    '</svg>',
            },
            {
                title: '2 Колонки',
                number: 2,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M3 3m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1zm9 -1v18"></path>\n' +
                    '</svg>',
            },
            {
                title: '3 Колонки',
                number: 3,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M3 3m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1zm6 -1v18m6 -18v18"></path>\n' +
                    '</svg>',
            },
            {
                title: 'Смена расположения',
                number: 90,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M8 2h-4a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2z" stroke-width="0" fill="currentColor"></path>\n' +
                    '   <path d="M20 14h-4a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2z" stroke-width="0" fill="currentColor"></path>\n' +
                    '   <path d="M16.707 2.293a1 1 0 0 1 .083 1.32l-.083 .094l-1.293 1.293h3.586a3 3 0 0 1 2.995 2.824l.005 .176v3a1 1 0 0 1 -1.993 .117l-.007 -.117v-3a1 1 0 0 0 -.883 -.993l-.117 -.007h-3.585l1.292 1.293a1 1 0 0 1 -1.32 1.497l-.094 -.083l-3 -3a.98 .98 0 0 1 -.28 -.872l.036 -.146l.04 -.104c.058 -.126 .14 -.24 .245 -.334l2.959 -2.958a1 1 0 0 1 1.414 0z" stroke-width="0" fill="currentColor"></path>\n' +
                    '   <path d="M3 12a1 1 0 0 1 .993 .883l.007 .117v3a1 1 0 0 0 .883 .993l.117 .007h3.585l-1.292 -1.293a1 1 0 0 1 -.083 -1.32l.083 -.094a1 1 0 0 1 1.32 -.083l.094 .083l3 3a.98 .98 0 0 1 .28 .872l-.036 .146l-.04 .104a1.02 1.02 0 0 1 -.245 .334l-2.959 2.958a1 1 0 0 1 -1.497 -1.32l.083 -.094l1.291 -1.293h-3.584a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-3a1 1 0 0 1 1 -1z" stroke-width="0" fill="currentColor"></path>\n' +
                    '</svg>',
            },
            {
                title: 'Смена соотношения',
                number: 91,
                svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                    '   <path d="M6 4l-3 3l3 3"></path>\n' +
                    '   <path d="M18 4l3 3l-3 3"></path>\n' +
                    '   <path d="M4 14m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>\n' +
                    '   <path d="M10 7h-7"></path>\n' +
                    '   <path d="M21 7h-7"></path>\n' +
                    '</svg>',
            }
        ];

        return availableSettings;
    }

    renderSettings() {
        return this.settings.map(setting => ({
            icon: setting.svg,
            label: setting.title,
            //confirmation: this.editors.cols.length > setting.number ? true : false,
            onActivate: () => this._updateCols(setting.number),
            isDisabled: setting.number == 91 ? (setting.number == 91 && this.editors.cols.length == 2 ? false : true) : false,
            closeOnActivate: true,
            isActive: this.editors.cols.length === setting.number,
        }));
    }

    static get toolbox() {
        return {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path><path d="M12 4l0 16"></path></svg>',
            title: 'Колонки',
        };
    }
}

export { EditorJsColumns as default };
