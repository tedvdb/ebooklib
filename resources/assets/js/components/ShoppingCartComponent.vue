<template>
    <div id="shoppingcartlist">
        <h2><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Download cart</h2>
        <ul>
           <li v-for="shoppingcartbook in shoppingcartbooks">
               {{ shoppingcartbook.creator }} - {{ shoppingcartbook.title }}
               <span class="glyphicon glyphicon-remove" aria-hidden="true" v-on:click="removeFromCart(shoppingcartbook)"></span>
           </li>
        </ul>
        <a href="#">Download all</a>
    </div>
</template>

<script>
    export default {
        computed: {
            shoppingcartbooks () {
                return this.$store.state.shoppingcartbooks
            }
        },
        methods: {
            async removeFromCart(book) {
                const { data } = await axios.get('/removeFromCart/'+book.id);
                this.$store.commit('REMOVE_BOOK', book.id);
            }
        }
    }
</script>
