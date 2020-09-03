<script src="./jquery-3.5.1.js"></script>
<script src="./bootstrap/js/bootstrap.js"></script>
<script>
    const cart_count = $('.cart-count'); // span tag
    const cart_short_list = $('.cart-short-list');

    $.get('handle-cart.php', function(data) {
        setCartCount(data);
    }, 'json');

    function setCartCount(data) {
        let count = 0;
        cart_short_list.empty();
        if (data && data.cart && data.cart.length) {
            for (let i in data.cart) {
                let item = data.cart[i];
                count += item.quantity;
                cart_short_list.append(`<a class="dropdown-item" href="#">${item.bookname} ${item.quantity}</a>`)
            }
            cart_count.text(count);
        }
    }
</script>