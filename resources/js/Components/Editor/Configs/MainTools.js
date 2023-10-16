import ItemPropTune from '../Plugins/ItemProp/index'
import Marker from '../Plugins/Marker/index';
import Column from '../Plugins/Column/index';
import Text from '../Plugins/Text/index';
import Alert from '../Plugins/Alert/index';
import Gallery from '../Plugins/Gallery/index';
import Spoiler from '../Plugins/Spoiler/index';
import Attachment from '../Plugins/Attachment/index';
import Card from '../Plugins/Card/index';
import Header from '../Plugins/Header/index';
import News from '../Plugins/News/index';

import List from '@editorjs/list';
import NestedList from '../Plugins/NestedList/index';

import Raw from '@editorjs/raw';
import Table from '@editorjs/table';
import Color from 'editorjs-text-color-plugin';
import Quote from '@editorjs/quote';

import ColumnTools from './ColumnTools';
import SpoilerTools from './SpoilerTools';

const MainTools = {
    itemProp: ItemPropTune,

    alert: {
        class: Alert,
        inlineToolbar: true,
        tunes: ['itemProp'],
    },

    gallery: Gallery,

    attachment: {
        class: Attachment,
        tunes: ['itemProp'],
    },

    marker: {
        class: Marker,
        shortcut: 'CMD+SHIFT+M',
        tunes: ['itemProp'],
    },

    color: {
        class: Color,
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

    header: {
        class: Header,
        config: {
            placeholder: 'Введите заголовок',
            levels: [2, 3],
            defaultLevel: 2
        },
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

    raw: {
        class: Raw,
        tunes: ['itemProp'],
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
            tools : ColumnTools,
        },
        tunes: ['itemProp'],
    },

    spoiler: {
        class: Spoiler,
        config : {
            tools : SpoilerTools,
        },
        tunes: ['itemProp'],
    },

    card: {
        class: Card,
        tunes: ['itemProp'],
    },

    news: {
        class: News,
        tunes: ['itemProp'],
    },
}

export { MainTools as default };
