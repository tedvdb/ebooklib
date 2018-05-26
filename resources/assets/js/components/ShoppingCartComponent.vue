<template>
    <div id="shoppingcartlist">
        <h2><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Download cart</h2>
        <ul>
           <li v-for="shoppingcartbook in shoppingcartbooks">
               {{ shoppingcartbook.creator }} - {{ shoppingcartbook.title }}
               <span class="glyphicon glyphicon-remove" aria-hidden="true" @click="removeFromCart(shoppingcartbook)"></span>
           </li>
        </ul>
        <a href="#">Download all</a>
    </div>
</template>

<script>
    export default {
        props: ['cartData'],
        /*mounted() {
            eventHub.$on('update:shoppingcartbooks', function(data) {
                console.log('newcartcontent');
                console.log(this);
                console.log(data);
            });
        },*/
        mutations: {
            REMOVE_BOOK (state, bookId) {
                shoppingcartbooks = shoppingcartbooks.filter(book => book.id !== bookId)
            },
            ADD_BOOK (state, book) {
                shoppingcartbooks.append(book);
            }
        },
        data() {
            return {
                shoppingcartbooks: this.cartData
            };
        },
        methods: {
            removeFromCart({commit, state}, book) {
                console.log(book);
                axios.get('/removeFromCart/' + book.id)
                    .then(function (response) {
                        commit('REMOVE_BOOK', book.id)
                    });
            }
        }
         /*   removeFromCart: function(event) {
                var bookid = $(event.target).data('bookid');
                axios.get('/removeFromCart/'+bookid)
                    .then(function(response) {
                        eventHub.$emit('update:shoppingcartbooks', response.data);
                    });
            }
        }*/
    }
</script>
