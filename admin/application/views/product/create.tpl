<div class="row">
   <div class="col-sm-12">
                       
        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">
        
            <div class="nav-tabs-custom ">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab1" data-toggle="tab">Основные данные</a></li>
                  <li><a href="#tab2" data-toggle="tab">Изображения</a></li>
                  <li><a href="#tab3" data-toggle="tab">Габариты</a></li>
                </ul>
            
    
                <div class="tab-content">

                   <div class="tab-pane active" id="tab1">
                       
                       <div class="box-body">
                        {if $model.errorSummary}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="callout callout-danger">
                                        <h4>Ошибки!</h4>
                                        {$model.errorSummary}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/if}
                        <div class="row">
                            <div class="col-sm-6">

                               <div class="row">
                                   <div class="col-sm-12">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group {if isset($model.error.product_name)}has-error{/if}">
                                                    <label for="{$model.id.product_name}">
                                                        {$model.label.product_name}
                                                    </label>
                                                    <input
                                                            type="text"
                                                            class="form-control"
                                                            id="{$model.id.product_name}"
                                                            name="{$model.name.product_name}"
                                                            value="{$model.value.product_name}"
                                                            placeholder="Введите название">
                                                    {if isset($model.error.product_name)}
                                                        <p class="help-block">{' '|implode:$model.error.product_name}</p>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group {if isset($model.error.product_sku)}has-error{/if}">
                                                    <label for="{$model.id.product_sku}">
                                                        {$model.label.product_sku}
                                                    </label>
                                                    <input
                                                            type="text"
                                                            class="form-control"
                                                            id="{$model.id.product_sku}"
                                                            name="{$model.name.product_sku}"
                                                            value="{$model.value.product_sku}"
                                                            placeholder="Введите артикул">
                                                    {if isset($model.error.product_sku)}
                                                        <p class="help-block">{' '|implode:$model.error.product_sku}</p>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group {if isset($model.error.category)}has-error{/if}">
                                                    <label for="{$model.id.category}">
                                                        {$model.label.category}
                                                    </label>
                                                    <select
                                                            class="form-control"
                                                            id="{$model.id.category}"
                                                            name="{$model.name.category}">

                                                    </select>
                                                    {if isset($model.error.category)}
                                                        <p class="help-block">{' '|implode:$model.error.category}</p>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group {if isset($model.error.manufacturer)}has-error{/if}">
                                                    <label for="{$model.id.manufacturer}">
                                                        {$model.label.manufacturer}
                                                    </label>
                                                    <select
                                                            class="form-control"
                                                            id="{$model.id.manufacturer}"
                                                            name="{$model.name.manufacturer}">

                                                    </select>
                                                    {if isset($model.error.manufacturer)}
                                                        <p class="help-block">{' '|implode:$model.error.manufacturer}</p>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group {if isset($model.error.description_short)}has-error{/if}">
                                                    <label for="{$model.id.description_short}">
                                                        {$model.label.description_short}
                                                    </label>
                                                    <textarea
                                                            class="form-control"
                                                            id="{$model.id.description_short}"
                                                            name="{$model.name.description_short}"
                                                            value="{$model.value.description_short}"></textarea>
                                                    {if isset($model.error.description_short)}
                                                        <p class="help-block">{' '|implode:$model.error.description_short}</p>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="col-sm-6">

                                <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.slug)}has-error{/if}">
                                            <label for="{$model.id.slug}">
                                                {$model.label.slug}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.slug}"
                                                    name="{$model.name.slug}"
                                                    value="{$model.value.slug}"
                                                    placeholder="Введите slug">
                                            {if isset($model.error.slug)}
                                                <p class="help-block">{' '|implode:$model.error.slug}</p>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                               <div class="icheckbox_flat-green checked" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                   <input type="checkbox" checked="" style="position: absolute; opacity: 0;">
                                                   <input type="checkbox" class="flat-red" name="{$model.name.status}" {if $model.value.status}checked{/if} value="true">
                                                   <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                               </div>
                                                {$model.label.status}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group {if isset($model.error.description_long)}has-error{/if}">
                                    <label for="{$model.id.description_long}">
                                        {$model.label.description_long}
                                    </label>
                                    <textarea
                                            class="form-control"
                                            id="{$model.id.description_long}"
                                            name="{$model.name.description_long}"
                                            value="{$model.value.description_long}"></textarea>
                                    {if isset($model.error.description_short)}
                                        <p class="help-block">{' '|implode:$model.error.description_long}</p>
                                    {/if}
                                </div>
                           </div>

                       </div>

                        <div class="row">
                            <div class="col-sm-6">

                                <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.meta_keywords)}has-error{/if}">
                                            <label for="{$model.id.meta_keywords}">
                                                {$model.label.meta_keywords}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.meta_keywords}"
                                                    name="{$model.name.meta_keywords}"
                                                    value="{$model.value.meta_keywords}">
                                            {if isset($model.error.meta_keywords)}
                                                <p class="help-block">{' '|implode:$model.error.meta_keywords}</p>
                                            {/if}
                                        </div>  
                                    </div>
                                </div>    

                                <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.meta_description)}has-error{/if}">
                                            <label for="{$model.id.meta_description}">
                                                {$model.label.meta_description}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.meta_description}"
                                                    name="{$model.name.meta_description}"
                                                    value="{$model.value.meta_description}">
                                            {if isset($model.error.meta_description)}
                                                <p class="help-block">{' '|implode:$model.error.meta_description}</p>
                                            {/if}
                                        </div>  
                                    </div>
                                </div>

                            </div> 

                        </div> 

                    </div>
                       
                   </div>

                   <div class="tab-pane" id="tab2">
                       
                       {if ($model.value.newRecord)}
                           <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group {if isset($model.error.picture)}has-error{/if}">
                                        <label for="{$model.id.picture}">
                                            {$model.label.picture}
                                        </label>
                                        <input
                                                type="file"
                                                id="{$model.id.picture}"
                                                name="{$model.name.picture}"
                                                value="">
                                        {if isset($model.error.picture)}
                                            <p class="help-block">{' '|implode:$model.error.picture}</p>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        {else}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group {if isset($model.error.picture)}has-error{/if}">
                                        <label for="{$model.id.picture}">
                                            {$model.label.picture}
                                        </label>
                                        <input
                                                type="file"
                                                id="{$model.id.picture}"
                                                name="{$model.name.picture}"
                                                value="">
                                        {if isset($model.error.picture)}
                                            <p class="help-block">{' '|implode:$model.error.picture}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Загруженное изображение</label>
                                        <p class="form-control-static">
                                            {if $model.value.picture_id>0}
                                                <img src="{$model.value.picture_id|pictureId2path}" style="width: 50px">
                                            {else}
                                                Нет изображения
                                            {/if}
                                    </div>
                                </div>
                            </div>
                        {/if}
                       
                   </div>

                   <div class="tab-pane" id="tab3">
                       
                       <div class="row">
                       
                           <div class="col-sm-6">

                                <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.product_width)}has-error{/if}">
                                            <label for="{$model.id.product_width}">
                                                {$model.label.product_weight}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.product_width}"
                                                    name="{$model.name.product_width}"
                                                    value="{$model.value.product_width}"
                                                    placeholder="см">
                                            {if isset($model.error.product_width)}
                                                <p class="help-block">{' '|implode:$model.error.product_width}</p>
                                            {/if}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.product_height)}has-error{/if}">
                                            <label for="{$model.id.product_height}">
                                                {$model.label.product_height}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.product_height}"
                                                    name="{$model.name.product_height}"
                                                    value="{$model.value.product_height}"
                                                    placeholder="см">
                                            {if isset($model.error.product_height)}
                                                <p class="help-block">{' '|implode:$model.error.product_height}</p>
                                            {/if}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.product_length)}has-error{/if}">
                                            <label for="{$model.id.product_length}">
                                                {$model.label.product_length}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.product_length}"
                                                    name="{$model.name.product_length}"
                                                    value="{$model.value.product_length}"
                                                    placeholder="см">
                                            {if isset($model.error.product_length)}
                                                <p class="help-block">{' '|implode:$model.error.product_length}</p>
                                            {/if}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group {if isset($model.error.product_weight)}has-error{/if}">
                                            <label for="{$model.id.product_weight}">
                                                {$model.label.product_weight}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="{$model.id.product_weight}"
                                                    name="{$model.name.product_weight}"
                                                    value="{$model.value.product_weight}"
                                                    placeholder="гр">
                                            {if isset($model.error.product_weight)}
                                                <p class="help-block">{' '|implode:$model.error.product_weight}</p>
                                            {/if}
                                        </div>
                                    </div>
                                </div>

                           </div>
                       
                       </div>
                       
                   </div>

                </div>
            
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            
            </div>
            
        </form>
        
    </div>
</div>
    
{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}