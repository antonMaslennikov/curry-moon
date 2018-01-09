<form action="/admin/product_category/field_save" id="category-field-form" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Дополнительные поля</h4>
    </div>
    <div class="modal-body">

            <div class="row">
                <div class="col-sm-12">
                   <div class="form-group">
                       <label for="pic-edit-title">Название поля <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="data[name]" required="required" value="{$field->name}"> 
                   </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                   <div class="form-group">
                       <label for="pic-edit-alt">Slug</label>
                           <input type="text" class="form-control" name="data[slug]" value="{$field->slug}">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                       <label>Допустимые значения (через запятую) <span class="text-danger">*</span></label>

                       {foreach from=$range item="k"}
                       <div class="form-group">
                           <div class="row">
                               <div class="col-sm-4">
                                   <input type="text" class="form-control" placeholder="slug" name="data[value][{$k}][slug]" value="{$field->value.$k.slug}">
                               </div>
                               <div class="col-sm-8">
                                   <input type="text" class="form-control" placeholder="название" name="data[value][{$k}][value]" value="{$field->value.$k.value}">
                               </div>
                           </div>
                       </div>
                       {/foreach}
                </div>
            </div>

    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="{$field->id}">
        <input type="hidden" name="data[category_id]" value="{$category}">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-success pull-right" type="submit">Сохранить</button>
    </div>
</form>