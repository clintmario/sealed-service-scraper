@foreach($lesson['lesson'] as $lessonIndex => $lessonObj)
<div id="{{ $initialName }}-lessons_div-{{ uniqid() }}" class="cm-generic-entity-Lesson">
    <small class="cm-entity-label"><span>Lesson {{ $lessonIndex + 1 }}</span></small>
    <div style="clear:both;"></div>
    <select id="{{ $initialName }}-tag-group-{{ uniqid() }}" name="lesson[lesson][{{ $lessonIndex }}][tag_group_id]" onchange="getTagsInTagGroup(this);" required style="float:left; width:40%;">
        @php
            $values = $object->getTagGroups();
        @endphp
            <option value="">Select Tag Group</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lessonObj['tag_group_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="lesson[lesson][{{ $lessonIndex }}][tag_id]" onchange="getLessonsInTag(this);" required style="float:left; margin-left: 10px; width:40%;">
        @php
            $values = [];
            if (!empty($lessonObj['tag_group_id'])) {
                $values = $object->getTagsInTagGroup($lessonObj['tag_group_id']);
            }
        @endphp
        <option value="">Select Tag</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lessonObj['tag_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="lesson[lesson][{{ $lessonIndex }}][lesson_id]" required style="float:left; margin-left: 10px; width:80%;">
        @php
            $values = [];
            if (!empty($lessonObj['tag_id'])) {
                $values = $object->getLessonsInTag($lessonObj['tag_id']);
            }
        @endphp
        <option value="">Select Lesson</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lessonObj['lesson_id'] == $key ? "selected" : "" }}>{{ $value->name }}</option>
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
