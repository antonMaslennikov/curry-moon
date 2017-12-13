<p class="login-box-msg">Авторизуйтесь для начала работы</p>

{if $model.errorSummary}
    <div class="row">
        <div class="col-sm-12">
            <div class="callout callout-danger">
                <p class="login-box-msg">
                {$model.errorSummary}
                </p>
            </div>
        </div>
    </div>
{/if}

<form method="post">
    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="{$model.name.email}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Пароль"  name="{$model.name.password}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    <input type="checkbox" name="{$model.name.rememberMe}" {if $model.value.rememberMe}checked{/if}> Запомнить меня
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
        </div>
        <!-- /.col -->
    </div>
</form>