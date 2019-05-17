@foreach($tag['tag'] as $tagIndex => $tagObj)
<div id="{{ $initialName }}-tags_div-{{ uniqid() }}" class="cm-generic-entity-Tag">
    <small class="cm-entity-label"><span>Tag {{ $tagIndex + 1 }}</span></small>
    <div style="clear:both;"></div>
    <select id="{{ $initialName }}-tag-group-{{ uniqid() }}" name="tag[tag][{{ $tagIndex }}][tag_group_id]" onchange="getTagsInTagGroup(this);" required style="float:left; width:40%;">
        @php
            $values = $object->getTagGroups();
        @endphp
            <option value="">Select Tag Group</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $tagObj['tag_group_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="tag[tag][{{ $tagIndex }}][tag_id]" required style="float:left; margin-left: 10px; width:40%;">
        @php
            $values = [];
            if (!empty($tagObj['tag_group_id'])) {
                $values = $object->getTagsInTagGroup($tagObj['tag_group_id']);
            }
        @endphp
        <option value="">Select Tag</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $tagObj['tag_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    @include("Fortuna.CMS::Entity.entity-actions")
    <div style="clear:both;"></div>
    @if ($errors->has($initialName))
        <span class="help-block">
            <strong>{{ $errors->first($initialName) }}</strong>
        </span>
    @endif
</div>
@endforeach
