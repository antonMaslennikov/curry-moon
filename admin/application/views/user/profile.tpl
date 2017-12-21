<div class="row">
    <div class="col-sm-12" style="margin-bottom: 20px;">
        <a href="/admin/user/update?id={$user.info.id}" class="btn btn-warning btn-sm">Редактировать</a>
        <a href="/admin/user/list" class="btn btn-success btn-sm">Список пользователей</a>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Основные данные</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th class="col-sm-4">ФИО:</th>
                        <td>{$user.info.user_name}</td>
                    </tr>
                    <tr>
                        <th>Логин:</th>
                        <td>{$user.info.user_login}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{$user.info.user_email}</td>
                    </tr>
                    <tr>
                        <th>Телефон:</th>
                        <td>{$user.info.user_phone}</td>
                    </tr>
                    <tr>
                        <th>Дата рождения:</th>
                        <td>{$user.info.user_birthday|date2ru_format:'Y-m-d':'d.m.Y'}</td>
                    </tr>
                    <tr>
                        <th>Описание:</th>
                        <td>{$user.info.description}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Адрес</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th class="col-sm-4">Индекс:</th>
                        <td>{$user.info.user_zip}</td>
                    </tr>
                    <tr>
                        <th>Страна:</th>
                        <td>{$user.country}</td>
                    </tr>
                    <tr>
                        <th>Населенный пункт:</th>
                        <td>{$user.city}</td>
                    </tr>
                    <tr>
                        <th>Адрес:</th>
                        <td>{$user.info.user_address}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Служебная информация</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th class="col-sm-3">Дата регистрации:</th>
                        <td>{$user.info.user_register_date|date2ru_format}</td>
                    </tr>
                    <tr>
                        <th>Последний вход:</th>
                        <td>{$user.info.user_last_login|date2ru_format}</td>
                    </tr>
                    <tr>
                        <th>IP адрес:</th>
                        <td>{$user.info.user_ip|long2ip}</td>
                    </tr>
                    <tr>
                        <th class="col-sm-3">Статуc пользователя:</th>
                        <td>{$statusList[$user.info.user_status]}</td>
                    </tr>
                    <tr>
                        <th>Активация пользователя:</th>
                        <td>{$activationList[$user.info.user_activation]}</td>
                    </tr>
                    <tr>
                        <th>Статус подписки:</th>
                        <td>{$subscribeList[$user.info.user_subscription_status]}</td>
                    </tr>
                    <tr>
                        <th>Тип регистрации:</th>
                        <td>
                            {if $user.info.user_is_fake == 'true'}
                                <label class="label label-success">Обычная регистрация</span>
                            {else}
                                <label class="label label-warning">Регистрация через заказ</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><a href="/admin/orders/list?filters[user_id]={$user.info.id}" target="_blank">Заказы</a></th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>