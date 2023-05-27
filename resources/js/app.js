import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import PrimeVue from 'primevue/config';
import Paginator from 'primevue/paginator';

import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'   // optional
import 'primevue/resources/themes/lara-light-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import Toolbar from 'primevue/toolbar';
import FileUpload from 'primevue/fileupload';
import RadioButton from 'primevue/radiobutton';
// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - WeShare`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue)
            .use(ZiggyVue, Ziggy)
            .use(ToastService)
            .component("Paginator", Paginator)
            .component("DataView", DataView)
            .component("DataViewLayoutOptions", DataViewLayoutOptions)
            .component("Button", Button)
            .component("DataTable", DataTable)
            .component("Column", Column)
            .component("Toast", Toast)
            .component("Tag", Tag)
            .component("InputText", InputText)
            .component("Dropdown", Dropdown)
            .component("Dialog",Dialog)
            .component("Toolbar",Toolbar)
            .component("FileUpload",FileUpload)
            .component("RadioButton", RadioButton)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

