import './bootstrap';
import {createApp} from 'vue';

import LessonEditor from './components/LessonEditor.vue'

const app = createApp();
app.component('lesson-editor', LessonEditor);
app.mount('#admin_app');
