import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import axios from 'axios';

import { createApp } from "vue";
import BookList from "./Components/BookList.vue";
import BookForm from "./Components/BookForm.vue";

window.axios = axios;
const app = createApp({});
app.use(useToast);

app.component("book-list", BookList);
app.component("book-form", BookForm);

app.mount("#app");