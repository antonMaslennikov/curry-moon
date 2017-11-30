<section id="gkMainbody" style="font-size: 100%;">
    <div id="vmMainPageOPC">
        <h1><div class="componentheading">Регистрация почти завершена!</div></h1>
        {if !$error}
        <p>Осталось только подтвердить свою регистрацию. Перейдите по ссылке в письме, которое мы отправили вам на почту.</p>
        {else}
        <p class="registration-error">{$error}</p>
        {/if}
    </div>
</section>


{literal}
<style>
    .registration-error {
        margin:20px 0;
        padding:20px;
        background-color: antiquewhite;
        color: red;
    }
</style>
{/literal}