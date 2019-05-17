<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472">
    <div class="dark_links wpb_column vc_column_container">
        <div class="vc_column-inner vc_custom_1472575963813">
            <div class="wpb_wrapper">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <form class="form-horizontal" method="POST" action="{{ url('/cms/commit') }}">
                    {{ csrf_field() }}
                <h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;color: #333333;font-size: 30px;">
                    <span><strong style="color:#003366;">{{ ucfirst($commitType) }} Commits</strong></span>
                </h2>
                <select name="cms_commit_type" onchange="window.location.assign(window.location.href.split(/[?#]/)[0] + '?commit_type=' + this.value);">
                    <option value="all" {{ ($commitType == "all") ? "selected" : "" }}>All</option>
                    <option value="my" {{ ($commitType == "my") ? "selected" : "" }}>Mine</option>
                </select>
                <div class="boc_divider_holder">
                    <div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div>
                </div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Active list.</p>
                    </div>
                </div>
                <table class="fortuna_table" width="100%">
                    <tbody>
                    @if(!empty($objects))
                    <tr>
                        <th>
                            <input type="checkbox" id="commit_object_toggle" name="commit_object_toggle" checked="checked" value="1" onclick="toggleCommitElements(this);">
                        </th>
                        <th>No</th>
                        @foreach(array_keys((array) $objects[0]) as $columnName)
                        <th>{{ ucfirst($columnName) }}</th>
                        @endforeach
                    </tr>
                    @foreach($objects as $idx => $object)
                    <tr {{ ($idx % 2 == 1) ? 'class=odd' : '' }}>
                        <td>
                            <input type="checkbox" id="commit_object_{{ ($idx + 1) }}" name="committed_object_ids[]" checked="checked" value="{{ $object->id }}">
                        </td>
                        <td>{{ ($idx + 1) }}</td>
                        @foreach(array_keys((array)$object) as $attribute)
                        <td>{{ $object->$attribute }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="boc_spacing " style="height: 20px"></div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="button  btn_large btn_royalblue btn_rounded icon_pos_before">
                            <i class="icon icon-paperplane2"></i><span>&nbsp;&nbsp;Commit</span>
                        </button>
                        <a class="btn btn-link" href="{{ url('/cms') }}">
                            Cancel
                        </a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>