<form role="form" method="post" enctype="multipart/form-data">
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Ответ на сообщение "{$fb.feedback_topic|crop_str:40}..."</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Дата</th>
                    <td>{$fb.feedback_date|date2ru_format}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{if $fb.feedback_user} <a href="admin/user/profile?id={$fb.feedback_user}" target="_blank">{$fb.feedback_email}</a>
                        {else}{$fb.feedback_email}{/if}</td>
                </tr>
                <tr>
                    <th>ФИО</th>
                    <td>{if $fb.feedback_user} <a href="admin/user/profile?id={$fb.feedback_user}" target="_blank">{$fb.feedback_name}</a>
                        {else}{$fb.feedback_name}{/if}</td>
                </tr>
                <tr>
                    <th>Тема</th>
                    <td>{$fb.feedback_topic}</td>
                </tr>
                <tr>
                    <th>Текст</th>
                    <td>{$fb.feedback_text}</td>
                </tr>
            </tbody>
        </table>
    {if $model.errorSummary}
        <div class="row">
            <div class="col-sm-12">
                <div class="callout callout-danger">
                    <h4>Ошибки!</h4>
                    {$model.errorSummary}
                </div>
            </div>
        </div>
    {/if}
        <div class="row">
            <div class="col-sm-12">
                <div class="callout callout-warning">
                    <p>Поля, отмеченные <span class="text-danger">*</span>, обязательны для заполнения!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {include file="adminlte/form/textarea.tpl" attr="feedback_reply" class_selector="tinymce-textarea" required="1"}
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Отправить ответ</button>
        <a href="/admin/feedback/list" class="btn btn-default">Отмена</a>
    </div>
</div>
</form>
{literal}
<script src="/public/packages/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    !function ($) {
        $(function() {

            tinymce.init({
                selector: '.tinymce-textarea',
                menubar: false
            });
        })
    }(window.jQuery)
</script>
{/literal}
{$fb|printr}
