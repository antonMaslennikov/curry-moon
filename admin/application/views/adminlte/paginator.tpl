{if $pagination.pageCount > 1}
<ul class="pagination pull-right" style="margin: 0 0 20px 0;">
    {$getArray.page=0}
    <li class="paginate_button previous {if $pagination.currentPage == 1}disabled{/if}">
        <a href="{currenturl page=0 }" class="first">&laquo;</a>
    </li>
    <li class="paginate_button previous {if $users.page.currentPage == 1}disabled{/if}">
        <a href="{currenturl page=$pagination.currentPage-1}" class="previous" data-action="previous">&lsaquo;</a>
    </li>
    <li><input type="text" readonly="readonly" value="Страница {$pagination.currentPage} из {$pagination.pageCount}" style="display: inline; text-align: center;
    float: left; position: relative; padding: 6px 12px; margin-left: -1px; line-height: 1.42857143;
    color: #337ab7; text-decoration: none; background-color: #fff; border: 1px solid #ddd;"/></li>
    <li class="paginate_button next {if $pagination.currentPage == $pagination.pageCount}disabled{/if}">
        <a href="{currenturl page=($pagination.currentPage+1)}" class="next" data-action="next">&rsaquo;</a>
    </li>
    <li class="paginate_button next {if $pagination.currentPage == $pagination.pageCount}disabled{/if}">
        <a href="{currenturl page=$pagination.pageCount}" class="last" data-action="last">&raquo;</a>
    </li>
</ul>
{/if}