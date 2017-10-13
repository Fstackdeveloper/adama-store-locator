{assign var="map_provider" value=$addons.adama_store_locator.map_provider}
{assign var="map_provider_api" value="`$map_provider`_map_api"}
{assign var="map_customer_templates" value="C"|fn_get_adama_store_locator_map_templates}
{assign var="map_container" value="map_canvas"}

{if $adama_store_locations}
    {if $map_customer_templates && $map_customer_templates.$map_provider}
        {include file=$map_customer_templates.$map_provider}
    {/if}

    <div class="ty-store-location">
        <div class="ty-store-location__map-wrapper" id="{$map_container}"></div>
        <div class="ty-wysiwyg-content ty-store-location__locations-wrapper" id="stores_list_box">
            {foreach from=$adama_store_locations item=loc key=num}
                <div class="ty-store-location__item" id="loc_{$loc.adama_store_location_id}">
                    <h3 class="ty-store-location__item-title" style="margin-bottom:8px;">{$loc.name}</h3>
                    

        {include file="common/image.tpl" image_width="500" image_height="" obj_id=$loc.adama_store_location_id images=$loc.main_pair}
        
        
        
                            <span class="ty-store-location__item-desc">{$loc.description nofilter}</span>


                    <div class="span16 " style="margin-bottom:8px;margin-top:8px;" >
                    {if $loc.city || $loc.country_title}
                       <div class="span8 "> <span class="ty-store-location__item-country" style="font-size:18px;font-weight: bold;">{if $loc.city}{$loc.city} - {/if}{$loc.country_title}</span></div>
                    {/if}
                    
                    
                    {if $loc.phone}
                       <div class="span8 " style="font-family: tahoma, arial, helvetica, sans-serif;font-size:18px;font-weight: normal;">  {$loc.phone} <i class="adama_icon adama_icon-mobile" style="font-size:20px;"></i></div>
                    {/if}
</div>


                    <div class="ty-store-location__item-view">
                        {include file="buttons/button.tpl" but_role="text" but_meta="cm-map-view-location ty-btn__tertiary" but_scroll="#map_canvas" but_text=__("view_on_map") but_extra="data-ca-latitude={$loc.latitude} data-ca-longitude={$loc.longitude}"}
                    </div>
                </div>
                {if $adama_store_locations|count > 1}
                    <hr />
                {/if}
            {/foreach}

            {if $adama_store_locations|count > 1}
                <div class="ty-store-location__item ty-store-location__item-all_stores">
                    <h3 class="ty-store-location__item-title">{__("all_stores")}</h3>
                    <div class="ty-store-location__item-view">{include file="buttons/button.tpl" but_scroll="#map_canvas" but_role="text" but_meta="cm-map-view-locations ty-btn__tertiary" but_text=__("view_on_map")}</div>
                </div>
            {/if}
        </div>
    </div>
{else}
    <p class="ty-no-items">{__("no_data")}</p>
{/if}

{capture name="mainbox_title"}{__("adama_store_locator")}{/capture}
