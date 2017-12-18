<div class="box">
    <div class="box-header">
        <h3 class="box-title">Просмотр сообщения "{$fb.feedback_topic|crop_str:40}..."</h3>
    </div>
    <div class="box-body">
        <h3>Информация об отправителе</h3>
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
        <h3>Информация об отправке</h3>
        <table class="table table-hover">
            <tbody>
            <tr>
                <th>Дата обработки</th>
                <td>{$fb.feedback_reply_date|date2ru_format}</td>
            </tr>
            <tr>
                <th>Кто обработал</th>
                <td>{$user.user_name}</td>
            </tr>
            <tr>
                <th>Ответ</th>
                <td>{$fb.feedback_reply}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <a href="/admin/feedback/list_send" class="btn btn-default">Вернуться</a>
    </div>
</div>