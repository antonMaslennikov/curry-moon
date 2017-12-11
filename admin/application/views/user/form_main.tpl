<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-warning">
            <p>Поля, отмеченные <span class="text-danger">*</span>, обязательны для заполнения!</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        {include file="adminlte/form/input.tpl" attr="user_name" required="1"}
    </div>
    <div class="col-sm-4">
        {include file="adminlte/form/input.tpl" attr="user_email" required="1"}
    </div>
    <div class="col-sm-4">
        {include file="adminlte/form/input.tpl" attr="user_phone"}
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="user_birthday" class_selector="datepicker"}
    </div>
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="user_description"}
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="user_status" list=$model.statusList}
    </div>
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="user_activation" list=$model.activationList}
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="user_is_fake" list=$model.isFakeList}
    </div>
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="user_subscription_status" list=$model.subscriptionStatusList}
    </div>
</div>