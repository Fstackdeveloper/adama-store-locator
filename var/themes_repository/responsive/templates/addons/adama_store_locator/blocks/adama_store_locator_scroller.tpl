{** block-description:adama_store_locator_scroller **}

{assign var="obj_prefix" value="`$block.block_id`000"}



<div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list">
    
{if $items}
{foreach from=$items item=loc key=num}

<div class="ty-scroller-list__item adama_locations">
            <div class="ty-scroller-list__img-block  adama_location_name">
           <a href="{"adama_store_locator.search?match=all&q=`$loc.name`"|fn_url}">     {$loc.name}</a>
            </div>
            <div class="ty-scroller-list__description  adama_location_description">
<i class="adama_icon adama_icon-mobile" style="font-size:20px;"></i> {$loc.phone}

            </div>
        </div>
        




{/foreach}
{/if}
    

    







</div>



{script src="js/lib/owlcarousel/owl.carousel.min.js"}
<script type="text/javascript">
(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
        var elm = context.find('#scroll_list_{$block.block_id}');

        $('.ty-float-left:contains(.ty-scroller-list),.ty-float-right:contains(.ty-scroller-list)').css('width', '100%');

        var item = {$block.properties.item_quantity|default:5},
            // default setting of carousel
            itemsDesktop = 4,
            itemsDesktopSmall = 3;
            itemsTablet = 2;

        if (item > 3) {
            itemsDesktop = item;
            itemsDesktopSmall = item - 1;
            itemsTablet = item - 2;
        } else if (item == 1) {
            itemsDesktop = itemsDesktopSmall = itemsTablet = 1;
        } else {
            itemsDesktop = item;
            itemsDesktopSmall = itemsTablet = item - 1;
        }

        var desktop = [1199, itemsDesktop],
            desktopSmall = [979, itemsDesktopSmall],
            tablet = [768, itemsTablet],
            mobile = [479, 1];

        {if $block.properties.outside_navigation == "Y"}
        function outsideNav () {
            if(this.options.items >= this.itemsAmount){
                $("#owl_outside_nav_{$block.block_id}").hide();
            } else {
                $("#owl_outside_nav_{$block.block_id}").show();
            }
        }
        {/if}

        if (elm.length) {
            elm.owlCarousel({
                direction: '{$language_direction}',
                items: item,
                itemsDesktop: desktop,
                itemsDesktopSmall: desktopSmall,
                itemsTablet: tablet,
                itemsMobile: mobile,
                scrollPerPage: true,
                autoPlay: false,
                lazyLoad: true,
                slideSpeed: {$block.properties.speed|default:400},
                stopOnHover: true,
                navigation: true,
                navigationText: ['{__("prev_page")}', '{__("next")}'],
                pagination: false,
            {if $block.properties.outside_navigation == "Y"}
                afterInit: outsideNav,
                afterUpdate : outsideNav
            });

              $("#owl_prev_`$obj_prefix`").click(function(){
                elm.trigger('owl.prev');
              });
              $("#owl_next_`$obj_prefix`").click(function(){
                elm.trigger('owl.next');
              });

            {else}
            });
            {/if}

        }
    });
}(Tygh, Tygh.$));
</script>


