import ItemPropTune from '../Plugins/ItemProp/index'
import Marker from '../Plugins/Marker/index';
import Text from '../Plugins/Text/index';
import Gallery from '../Plugins/Gallery/index';
import Column from '../Plugins/Column/index';
import Card from '../Plugins/Card/index';
import Attachment from '../Plugins/Attachment/index';
import Header from '../Plugins/Header/index'
import Spoiler from '../Plugins/Spoiler/index';

import List from '@editorjs/list';
import Raw from '@editorjs/raw';
import Color from 'editorjs-text-color-plugin';
import Quote from '@editorjs/quote';
import Table from '@editorjs/table';

import SpoilerSpoilerTools from './SpoilerSpoilerTools';

import SpoilerColumnTools from './SpoilerColumnTools';
import NestedList from '../Plugins/NestedList/index';

const SpoilerTools = {
    itemProp: ItemPropTune,

    gallery: { class: Gallery, tunes: ['itemProp'] },

    attachment: Attachment,

    marker: {
        class: Marker,
        shortcut: 'CMD+SHIFT+M',
    },

    table: {
        class: Table,
        inlineToolbar: true,
        config: {
            rows: 2,
            cols: 3,
        },
        tunes: ['itemProp'],
    },

    color: {
        class: Color, // if load from CDN, please try: window.ColorPlugin
        config: {
            colorCollections: ['#EC7878','#9C27B0','#673AB7','#3F51B5','#0070FF','#03A9F4','#00BCD4','#4CAF50','#8BC34A','#CDDC39', '#FFF'],
            defaultColor: '#FF1300',
            type: 'text'
        }
    },

    paragraph: {
        class: Text,
        inlineToolbar: true,
        tunes: ['itemProp'],
    },

    spoiler: {
        class: Spoiler,
        config : {
            tools : SpoilerSpoilerTools,
        },
        tunes: ['itemProp'],
    },

    header: {
        class: Header,
        config: {
            placeholder: 'Введите заголовок',
            levels: [2, 3],
            defaultLevel: 2
        },
        shortcut: 'CMD+SHIFT+H',
        tunes: ['itemProp'],
    },

    list: {
        class: List,
        inlineToolbar: true,
        tunes: ['itemProp'],
    },

    nestedList: {
        class: NestedList,
        inlineToolbar: true,
        config: {
            defaultStyle: 'ordered'
        },
        tunes: ['itemProp'],
    },

    raw: { class: Raw, tunes: ['itemProp'] },

    quote: {
        class: Quote,
        inlineToolbar: true,
        shortcut: 'CMD+SHIFT+O',
        config: {
            quotePlaceholder: 'Пишите текст',
            captionPlaceholder: 'Автор (Необязательно)',
        },
        tunes: ['itemProp'],
    },

    columns : {
        class : Column,
        config : {
            tools : SpoilerColumnTools,
        },
        tunes: ['itemProp'],
    },

    card: { class: Card, tunes: ['itemProp'] },
}

export { SpoilerTools as default };
