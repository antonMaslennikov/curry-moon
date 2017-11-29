{include file="adminlte/header.tpl"}
<div class="error-page">
        <h2 class="headline text-red">503</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

          <p>
            We will work on fixing that right away.
            Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search form.
          </p>

          <p>{$errorMessage}</p>
		</div>
      </div>
{include file="adminlte/footer.tpl"}