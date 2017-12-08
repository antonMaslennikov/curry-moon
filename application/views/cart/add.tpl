{if $status == 'ok'}
    <a class="continue_link" href="/ru/shop">Продолжить покупки</a>
    <a class="showcart floatright" href="/ru/cart">Показать корзину</a>
    <h4>{$quantity} x {$product->product_name} добавлен в Вашу корзину.</h4>
{else}

    <p>Ошибка: {$error}</p>

{/if}