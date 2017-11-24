{if $error}
	<div class="coupon-activate-message error">
	{$error.message}
	</div>
{/if}

{if $warning}
	<div class="coupon-activate-message warning">
	{$warning.message}
	</div>
{/if}

{literal}
<style>
	.coupon-activate-message {
		padding:150px;
		text-align:center;
		font-size:16px;
	}
	
	.coupon-activate-message.error {
		border:1px solid red;
		background-color:#fce0e0;
		color:red;
	}
	
	.coupon-activate-message.warning {
		border:1px solid #ffa100;
		background-color:#f7f3c3;
	}
</style>
{/literal}
