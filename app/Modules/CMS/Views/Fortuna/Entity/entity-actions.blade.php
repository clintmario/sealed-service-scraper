<div style="cursor:pointer; float:right;">
    @if(!empty($icons))
        @foreach($icons as $icon)
            @if($icon['type'] == 'edit')
                <div style="float:left; margin-top:5px;" onclick="window.open(gBaseUrl + '/cms/save?object_type=Quiz&object_id=' + $(this).parent().prev().val(), '_blank');">
                    <i class="icon  {{ $icon['name'] }}" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
                </div>
            @endif
        @endforeach
    @endif
    <div style="float:left; margin-top:5px;" onclick="moveGenericEntityUp(this);">
        <i class="icon  icon-arrow-circle-up" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
    </div>
    <div style="float:left; margin-top:5px;" onclick="moveGenericEntityDown(this);">
        <i class="icon   icon-arrow-circle-down" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
    </div>
    <div style="float:left; margin-top:5px;" onclick="removeGenericEntity(this);">
        <i class="icon  icon-times-circle" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
    </div>
    <div style="clear:both;"></div>
</div>