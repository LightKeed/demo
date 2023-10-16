class ItemPropTune {
    constructor({ data, api }) {
        this.api = api;
        this.propName = null;
        if(data) {
            this.propName = data.value;
        }
    }
    render() {
        return {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="8.5" cy="8.5" r="1" fill="currentColor"></circle><path d="M4 7v3.859c0 .537 .213 1.052 .593 1.432l8.116 8.116a2.025 2.025 0 0 0 2.864 0l4.834 -4.834a2.025 2.025 0 0 0 0 -2.864l-8.117 -8.116a2.025 2.025 0 0 0 -1.431 -.593h-3.859a3 3 0 0 0 -3 3z"></path></svg>',
            label: 'ItemProp',
            onActivate: () => {
                this.propName = prompt('Введите название ItemProp');
            },
            closeOnActivate: true,
            isActive: this.propName ? true : false
        };
    }

    save() {
        return {
            value: ''+ this.propName +''
        };
    }

    static get isTune() {
        return true;
    }
}

export { ItemPropTune as default };
