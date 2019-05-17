@foreach($test['test'] as $testIndex => $testObj)
<div id="{{ $initialName }}-tests_div-{{ uniqid() }}" class="cm-generic-entity-Test">
    <small class="cm-entity-label"><span>Test {{ $testIndex + 1 }}</span></small>
    <div style="clear:both;"></div>
    <select id="{{ $initialName }}-tag-group-{{ uniqid() }}" name="test[test][{{ $testIndex }}][tag_group_id]" onchange="getTagsInTagGroup(this);" required style="float:left; width:40%;">
        @php
            $values = $object->getTagGroups();
        @endphp
            <option value="">Select Tag Group</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $testObj['tag_group_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="test[test][{{ $testIndex }}][tag_id]" onchange="getQuizzesInTag(this);" required style="float:left; margin-left: 10px; width:40%;">
        @php
            $values = [];
            if (!empty($testObj['tag_group_id'])) {
                $values = $object->getTagsInTagGroup($testObj['tag_group_id']);
            }
        @endphp
        <option value="">Select Tag</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $testObj['tag_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="test[test][{{ $testIndex }}][quiz_id]" required style="float:left; margin-left: 10px; width:80%;">
        @php
            $values = [];
            if (!empty($testObj['tag_id'])) {
                $values = $object->getQuizzesInTag($testObj['tag_id']);
            }
        @endphp
        <option value="">Select Quiz</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $testObj['quiz_id'] == $key ? "selected" : "" }}>{{ $value->name }}</option>
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
