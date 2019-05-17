@foreach($track['track'] as $trackIndex => $trackObj)
<div id="{{ $initialName }}-tracks_div-{{ uniqid() }}" class="cm-generic-entity-Track">
    <small class="cm-entity-label"><span>Track {{ $trackIndex + 1 }}</span></small>
    <div style="clear:both;"></div>
    <select id="{{ $initialName }}-tag-group-{{ uniqid() }}" name="track[track][{{ $trackIndex }}][tag_group_id]" onchange="getTagsInTagGroup(this);" required style="float:left; width:40%;">
        @php
            $values = $object->getTagGroups();
        @endphp
            <option value="">Select Tag Group</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $trackObj['tag_group_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="track[track][{{ $trackIndex }}][tag_id]" onchange="getTracksInTag(this);" required style="float:left; margin-left: 10px; width:40%;">
        @php
            $values = [];
            if (!empty($trackObj['tag_group_id'])) {
                $values = $object->getTagsInTagGroup($trackObj['tag_group_id']);
            }
        @endphp
        <option value="">Select Tag</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $trackObj['tag_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="track[track][{{ $trackIndex }}][track_id]" required style="float:left; margin-left: 10px; width:80%;">
        @php
            $values = [];
            if (!empty($trackObj['tag_id'])) {
                $values = $object->getTracksInTag($trackObj['tag_id']);
            }
        @endphp
        <option value="">Select Track</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $trackObj['track_id'] == $key ? "selected" : "" }}>{{ $value->name }}</option>
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
