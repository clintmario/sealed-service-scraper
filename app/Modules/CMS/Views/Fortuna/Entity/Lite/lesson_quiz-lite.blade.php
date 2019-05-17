@foreach($lesson_quiz['lesson_quiz'] as $lesson_quizIndex => $lesson_quizObj)
<div id="{{ $initialName }}-lesson_quizzes_div-{{ uniqid() }}" class="cm-generic-entity-Lesson_quiz">
    <small class="cm-entity-label"><span>Lesson_quiz {{ $lesson_quizIndex + 1 }}</span></small>
    <div style="clear:both;"></div>
    <select id="{{ $initialName }}-tag-group-{{ uniqid() }}" name="lesson_quiz[lesson_quiz][{{ $lesson_quizIndex }}][tag_group_id]" onchange="getTagsInTagGroup(this);" required style="float:left; width:40%;">
        @php
            $values = $object->getTagGroups();
        @endphp
            <option value="">Select Tag Group</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lesson_quizObj['tag_group_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="lesson_quiz[lesson_quiz][{{ $lesson_quizIndex }}][tag_id]" onchange="getQuizzesInTag(this);" required style="float:left; margin-left: 10px; width:40%;">
        @php
            $values = [];
            if (!empty($lesson_quizObj['tag_group_id'])) {
                $values = $object->getTagsInTagGroup($lesson_quizObj['tag_group_id']);
            }
        @endphp
        <option value="">Select Tag</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lesson_quizObj['tag_id'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <select id="{{ $initialName }}-tag-{{ uniqid() }}" name="lesson_quiz[lesson_quiz][{{ $lesson_quizIndex }}][quiz_id]" required style="float:left; margin-left: 10px; width:75%;">
        @php
            $values = [];
            if (!empty($lesson_quizObj['tag_id'])) {
                $values = $object->getQuizzesInTag($lesson_quizObj['tag_id']);
            }
        @endphp
        <option value="">Select Quiz</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $lesson_quizObj['quiz_id'] == $key ? "selected" : "" }}>{{ $value->name }}</option>
        @endforeach
    </select>
    @php
        $icons = [];
        if(!empty($lesson_quizObj['quiz_id'])) {
            $icons = [
                [
                    'type' => 'edit',
                    'name' => 'icon-pencil2',
                ],
            ];
        }
    @endphp
    @include("Fortuna.CMS::Entity.entity-actions", [
        'icons' => $icons
    ])
    <div style="clear:both;"></div>
    @if ($errors->has($initialName))
        <span class="help-block">
            <strong>{{ $errors->first($initialName) }}</strong>
        </span>
    @endif
</div>
@endforeach
