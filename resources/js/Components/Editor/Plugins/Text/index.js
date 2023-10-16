/**
 * Build styles
 */
//  require('./index.css').toString();

/**
 * Base Text Block for the Editor.js.
 * Represents simple Text
 *
 * @author CK (team@codex.so)
 * @copyright Calumk123
 * @license The MIT License (MIT)
 */

/**
 * @typedef {object} TextConfig
 * @property {string} placeholder - placeholder for the empty Text
 * @property {boolean} preserveBlank - Whether or not to keep blank Texts when saving editor data
 */

/**
 * @typedef {Object} TextData
 * @description Tool's input and output data format
 * @property {String} text — Text's content. Can include HTML tags: <a><b><i>
 */


import style from './style.css?inline'
import { IconText } from '@codexteam/icons'

class Text {
    /**
     * Default placeholder for Text Tool
     *
     * @return {string}
     * @constructor
     */
    static get DEFAULT_PLACEHOLDER() {
        return 'Hello :)';
    }

    static get enableLineBreaks() {
        return true;
    }

    get sanitizerConfig() {
        return {
            span: false,
            font: true,
            br: true,
            b: {},
            a: {
                href: true
            },
            mark: {},
            i: {},
            div: {},
        }
    };

    /**
     * Render plugin`s main Element and fill it with saved data
     *
     * @param {object} params - constructor params
     * @param {TextData} params.data - previously saved data
     * @param {TextConfig} params.config - user config for Tool
     * @param {object} params.api - editor.js api
     * @param {boolean} readOnly - read only mode flag
     */
    constructor({data, config, api, readOnly}) {
        this.api = api;
        this.readOnly = readOnly;

        this._CSS = {
            wrapper: 'wdd-text-wrapper',
            textBlock: 'wdd-text',
        };

        if (!this.readOnly) {
            this.onKeyUp = this.onKeyUp.bind(this);
        }

        /**
         * Placeholder for Text if it is first Block
         * @type {string}
         */
        this._placeholder = config.placeholder ? config.placeholder : Text.DEFAULT_PLACEHOLDER;
        this._data = this.normalizeData(data);

        this._element = '';//this.drawView();
        this._icon = '';
        this._preserveBlank = config.preserveBlank !== undefined ? config.preserveBlank : false;
    }

    normalizeData(data) {
        const newData = {};

        newData.icon = data.icon || '';
        newData.text = data.text || '';
        newData.align = data.align || 'left';

        return newData;
    }

    /**
     * Check if text content is empty and set empty string to inner html.
     * We need this because some browsers (e.g. Safari) insert <br> into empty contenteditanle elements
     *
     * @param {KeyboardEvent} e - key up event
     */
    onKeyUp(e) {
        if (e.code !== 'Backspace' && e.code !== 'Delete') {
            return;
        }

        if (e.code !== '13') {
            console.log('key 13');
        }

        const { textContent } = this._element;

        console.log('wefwefwef');

        if (textContent === '') {
            this._element.innerHTML = '';
        }
    }

    /**
     * Return Tool's view
     *
     * @returns {HTMLDivElement}
     */
    render() {
        this.textWrapper = this._make('div', [this._CSS.wrapper], null);

        this.textWrapper.classList.add('text-' + this._data.align);

        let element = this._make('div', [this._CSS.textBlock], {
            contentEditable: !this.readOnly,
            innerHTML: this.api.sanitizer.clean(this._data.text || '', this.sanitizerConfig)
        });

        console.log(this.sanitizerConfig);

        // console.log(this.api.sanitizer.clean(this._data.text || '', this.sanitizerConfig));

        element.dataset.placeholder = this._placeholder;

        this._icon = this._make('div', ['wdd-icon'], null);
        this.textWrapper.appendChild(this._icon);

        if(this._data.icon != '') {
            this._icon.innerHTML = this._data.icon;
        }

        this._element = this.textWrapper.appendChild(element);

        this.textWrapper.addEventListener('paste', event => {
            event.stopImmediatePropagation();
        }, true);

        return this.textWrapper;
    }

    /**
     * Method that specified how to merge two Text blocks.
     * Called by Editor.js by backspace at the beginning of the Block
     * @param {TextData} data
     * @public
     */
    merge(data) {
        console.log('wrferfrf');

        let newData = {
            text : this._data.text + data.text,
            align: this._data.align,
        };

        this._data = newData;
    }

    /**
     * Validate Text block data:
     * - check for emptiness
     *
     * @param {TextData} savedData — data received after saving
     * @returns {boolean} false if saved data is not correct, otherwise true
     * @public
     */
    validate(savedData) {
        if (savedData.text.trim() === '' && !this._preserveBlank) {
            return false;
        }

        return true;
    }

    /**
     * Extract Tool's data from the view
     * @param {HTMLDivElement} toolsContent - Text tools rendered view
     * @returns {TextData} - saved data
     * @public
     */
    save(toolsContent) {
        if(this.api.sanitizer.clean(toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML || '', this.sanitizerConfig) != toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML && !toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML.includes("<span style=\"background-color: rgb(168, 214, 255);\">")) {
            toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML = this.api.sanitizer.clean(toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML || '', this.sanitizerConfig)
        }

        return {
            icon: this._data.icon,
            text: toolsContent.querySelector('.' + this._CSS.textBlock).innerHTML,
            align: this._data.align,
        };
    }

    /**
     * On paste callback fired from Editor.
     *
     * @param {PasteEvent} event - event with pasted data
     */
    onPaste(event) {
        const data = {
            text: event.detail.data.innerHTML
        };

        console.log('wedwed1122');

        this._data = data;
    }

    /**
     * Enable Conversion Toolbar. Text can be converted to/from other tools
     */
    static get conversionConfig() {
        return {
            export: 'text', // to convert Text to other block, use 'text' property of saved data
            import: 'text' // to covert other block's exported string to Text, fill 'text' property of tool data
        };
    }

    /**
     * Sanitizer rules
     */
    static get sanitize() {
        return {
            icon: true,
            text: {
                span: false,
                font: true,
                b: {},
                i: {},
                a: {
                    href: true
                },
                mark: {},
                br: true,
                div: {},
            }
        };
    }

    /**
     * Returns true to notify the core that read-only mode is supported
     *
     * @return {boolean}
     */
    static get isReadOnlySupported() {
        return true;
    }

    /**
     * Get current Tools`s data
     * @returns {TextData} Current data
     * @private
     */
    get data() {
        let text = this._element.innerHTML;

        console.log('1111111');

        this._data.text = text;

        return this._data;
    }

    /**
     * Store data in plugin:
     * - at the this._data property
     * - at the HTML
     *
     * @param {TextData} data — data to set
     * @private
     */
    set data(data) {
        this._data = data || {};
    }

    /**
     * Used by Editor paste handling API.
     * Provides configuration to handle P tags.
     *
     * @returns {{tags: string[]}}
     */
    static get pasteConfig() {
        return {
            tags: [ 'P' ]
        };
    }

    async _addIcon() {
        if(this._data.icon == '') {
            const result = await window.showPickIcon();

            if(result) {
                const stroke = result.svg.querySelector('path:first-child');
                result.svg.removeChild(stroke);
                result.svg.removeAttribute('class');
                result.svg.style.width = '27px';
                result.svg.style.height = '27px';
                result.svg.style.marginRight = '1rem';
                result.svg.style.color = '#0070ff';

                this._data.icon = result.svg.outerHTML;
                this._icon.appendChild(result.svg);
            } else {
                this._data.icon = '';
                this._icon.innerHTML = '';
            }
        } else {
            this._data.icon = '';
            this._icon.innerHTML = '';
        }
    }

    setAlign(align) {
        this._data.align = align;
        this.textWrapper.classList.remove('text-left', 'text-center', 'text-right');
        this.textWrapper.classList.add('text-' + this._data.align);
    }

    renderSettings() {
        let menu = [];

        menu.push({
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="-rotate-90" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M4 18v-4a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z"></path>
                   <path d="M12 9v-4"></path>
                   <path d="M10 7l4 0"></path>
                </svg>`,
            label: 'Добавить иконку',
            onConfirmation: this._data.icon != '',
            number: 1,
            onActivate: () => this._addIcon(),
            closeOnActivate: true,
            isActive: this._data.icon != '',
        });

        menu.push({
            icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                '   <path d="M4 6l16 0"></path>\n' +
                '   <path d="M4 12l10 0"></path>\n' +
                '   <path d="M4 18l14 0"></path>\n' +
                '</svg>',
            label: 'По левому краю',
            onActivate: () => this.setAlign('left'),
            closeOnActivate: true,
            isActive: this._data.align === 'left',
        });

        menu.push({
            icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-center" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                '   <path d="M4 6l16 0"></path>\n' +
                '   <path d="M8 12l8 0"></path>\n' +
                '   <path d="M6 18l12 0"></path>\n' +
                '</svg>',
            label: 'По центру',
            onActivate: () => this.setAlign('center'),
            closeOnActivate: true,
            isActive: this._data.align === 'center',
        });

        menu.push({
            icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                '   <path d="M4 6l16 0"></path>\n' +
                '   <path d="M10 12l10 0"></path>\n' +
                '   <path d="M6 18l14 0"></path>\n' +
                '</svg>',
            label: 'По правому краю',
            onActivate: () => this.setAlign('right'),
            closeOnActivate: true,
            isActive: this._data.align === 'right',
        });

        return menu;
    }

    /**
     * Icon and title for displaying at the Toolbox
     *
     * @return {{icon: string, title: string}}
     */
    static get toolbox() {
        return {
            icon: IconText,
            title: 'Текст'
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

export { Text as default }
