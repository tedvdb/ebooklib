
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuex = require('vuex');

Vue.component('shoppingcart-component', require('./components/ShoppingCartComponent'));

const store = new Vuex.Store({
    state: {
        shoppingcartbooks: currentShoppingCart
    },
    mutations: {
        REMOVE_BOOK (state, bookId) {
            state.shoppingcartbooks = state.shoppingcartbooks.filter(book => book.id !== bookId);
        },
        ADD_BOOK (state, book) {
            state.shoppingcartbooks.push(book);
        }
    },
});

const app = new Vue({
    el: 'div.list',
    computed: {
        shoppingcartbooks () {
            return store.state.shoppingcartbooks
        }
    },
    store,
    methods: {
        async addToCart(bookid) {
            const { data } = await axios.get('/addToCart/'+bookid);
            if(typeof data.id !== "undefined") {
                this.$store.commit('ADD_BOOK', data);
            }
        }
    }
});

