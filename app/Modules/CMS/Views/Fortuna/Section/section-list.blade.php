<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472">
    <div class="dark_links wpb_column vc_column_container">
        <div class="vc_column-inner vc_custom_1472575963813">
            <div class="wpb_wrapper">
                <h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;color: #333333;font-size: 30px;">
                    <span><strong style="color:#003366;">{{ $objectType }}</strong></span>
                </h2>
                <div class="boc_divider_holder">
                    <div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div>
                </div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Active list.</p>
                    </div>
                </div>
                <b style="font-weight: 600">Filter By:</b>
                <div id="cms-tag-elements" class="clearfix">
                    @foreach($cmsService->getFilterByTags($objectType) as $key => $tagGroup)
                        <select
                                id="{{ $key }}" name="{{ $key }}"
                                onchange="changeFilterForElements(this);" style="margin-right: 10px; float:left; @if(!empty($selectedFilters[$key])) background:#b8d260; @endif">
                            <option value="0">Select {{ $tagGroup }}</option>
                            @foreach($cmsService->getTagsInTagGroupByName($objectType, $tagGroup) as $tagId => $tagValue)
                                @php
                                    $selected = (!empty($selectedFilters[$key]) && $selectedFilters[$key] == $tagId) ? 'selected' : '';
                                @endphp
                            <option value="{{ $tagId }}"
                                {{ $selected }}>
                                {{ $tagValue }}
                            </option>
                            @endforeach
                        </select>
                    @endforeach
                </div>
                <div class="clearfix">
                    <div class="float:left">
                        <b style="font-weight: 600; float:left;">Search:&nbsp;&nbsp;&nbsp;</b> <input id="search_keywords" onfocus="this.value=''; this.style.backgroundColor='white'; return false;"
                                                                onkeydown="triggerSearch(event);" type="text" name="query_term"
                                                                class="" value="{{ $searchKeywords }}"
                                                                style="float: left; @if(!empty($searchKeywords)) background:#ffda8a; @endif " />
                    </div>
                    <button type="submit" style="float:left; margin-left:10px;" class="button  btn_large btn_royalblue btn_rounded icon_pos_before" onclick="changeFilterForElements(this); return false;">
                        <i class="icon icon-search"></i><span></span>
                    </button>
                </div>
                @if($numPages > 1)
                <div class="cms-table-pagination clearfix">
                    <ul class="clearfix">
                        <li><a href="" onclick="gotoPageNumber(1); return false;">&laquo;&laquo;</a></li>
                        <li><a href="" onclick="gotoPageNumber({{ $pageNumber - 1 }}); return false;">&laquo;</a></li>
                        @foreach ($pageNumberArray as $i)
                        <li><a href="" onclick="gotoPageNumber({{ $i }}); return false;" @if ($i == $pageNumber) class="current-page" @endif>{{ $i }}</a></li>
                        @endforeach
                        <li><a href="" onclick="gotoPageNumber({{ $pageNumber + 1 }}); return false;">&raquo;</a></li>
                        <li><a href="" onclick="gotoPageNumber({{ $numPages }}); return false;">&raquo;&raquo;</a></li>
                    </ul>
                </div>
                @endif
                <table class="fortuna_table" width="100%">
                    <tbody>
                    @if(!empty($objects))
                    <tr>
                        <th>No</th>
                        @foreach(array_keys((array) $objects[0]) as $columnName)
                        <th>{{ ucfirst($columnName) }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                    @foreach($objects as $idx => $object)
                    <tr {{ ($idx % 2 == 1) ? 'class=odd' : '' }}>
                        <td>{{ ($pageNumber - 1) * $pageSize + $idx + 1 }}</td>
                        @foreach(array_keys((array)$object) as $attribute)
                        <td>{{ $object->$attribute }}</td>
                        @endforeach
                        <td><a target="_blank" href="{{ url('/cms/save?object_type=' . urlencode($objectType) . '&object_id=' . $object->id) }}">Edit</a> |
                            <a target="_blank" href="{{ url('/cms/delete?object_type=' . urlencode($objectType) . '&object_id=' . $object->id) }}"
                               onclick="return confirm('Are you sure you want to delete this object?');">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                @if($numPages > 1)
                    <div class="cms-table-pagination clearfix">
                        <ul class="clearfix">
                            <li><a href="" onclick="gotoPageNumber(1); return false;">&laquo;&laquo;</a></li>
                            <li><a href="" onclick="gotoPageNumber({{ $pageNumber - 1 }}); return false;">&laquo;</a></li>
                            @foreach ($pageNumberArray as $i)
                                <li><a href="" onclick="gotoPageNumber({{ $i }}); return false;" @if ($i == $pageNumber) class="current-page" @endif>{{ $i }}</a></li>
                            @endforeach
                            <li><a href="" onclick="gotoPageNumber({{ $pageNumber + 1 }}); return false;">&raquo;</a></li>
                            <li><a href="" onclick="gotoPageNumber({{ $numPages }}); return false;">&raquo;&raquo;</a></li>
                        </ul>
                    </div>
                @endif
                <input type="hidden" name="cms_page_number" id="cms_page_number" value="{{ $pageNumber }}" />
                <input type="hidden" name="cms_object_type" id="cms_object_type" value="{{ $objectType }}" />
                <div class="boc_spacing " style="height: 20px"></div>
            </div>
        </div>
    </div>
</div>