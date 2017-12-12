<aside id="gkSidebar">
    <div>
        <div class="box "><h3 class="header">Архив публикаций</h3>
            <div class="content">
                <div id="k2ModuleBox721" class="k2ArchivesBlock">
                    <ul>
                        {foreach from=$archive item="a"}
                        <li>
                            <a href="/ru/date/{$a.y}/{$a.m}">{$a.month_name} {$a.y} <b>({$a.count})</b></a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
        <div class="box "><h3 class="header">Теги</h3>
            <div class="content">
                <div id="k2ModuleBox715" class="k2TagCloudBlock">
                    {foreach from=$tags1 item="t"}
                    <a href="/ru/tegi/{$t.slug}" title="{$t.count} материалов с тэгом {$t.name}">{$t.name}</a>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</aside>