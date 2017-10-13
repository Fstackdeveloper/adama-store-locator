{** block-description:adama_store_locator **}

<form action="{""|fn_url}" method="get" name="adama_store_locator_form">
    <div class="ty-control-group">
        <label for="adama_store_locator_search{$block.block_id}" class="ty-control-group__title">{__("search")}</label>

        <div class="ty-input-append ty-m-none">
            <input type="text" size="20" class="ty-input-text" id="adama_store_locator_search{$block.block_id}" name="q" value="{$adama_store_locator_search.q}" />
            {include file="buttons/go.tpl" but_name="adama_store_locator.search" alt=__("search")}
        </div>

    </div>
</form>
