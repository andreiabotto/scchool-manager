/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('user-management', require('./components/users/UserManagement.vue').default);
Vue.component('user-form', require('./components/users/UserForm.vue').default);

Vue.component('student-management', require('./components/students/StudentManagement.vue').default);
Vue.component('student-form', require('./components/students/StudentForm.vue').default);

Vue.component('class-management', require('./components/classrooms/ClassManagement.vue').default);
Vue.component('class-form', require('./components/classrooms/ClassForm.vue').default);

Vue.component('registration-management', require('./components/student_classroom/RegistrationManagement.vue').default);
Vue.component('registration-form', require('./components/student_classroom/RegistrationForm.vue').default);

Vue.component('login-button', require('./components/LoginButton.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
