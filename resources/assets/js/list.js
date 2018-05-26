
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('shoppingcart-component', require('./components/ShoppingCartComponent'));


const app = new Vue({
    el: 'div.list',
    methods: {
        addToCart({commit, state}, bookid) {
            axios.get('/addToCart/'+bookid)
            .then(function(response) {
                commit('REMOVE_BOOK', response.data)
                //eventHub.$emit('update:shoppingcartbooks', response.data);
            });
        }
    }
});
