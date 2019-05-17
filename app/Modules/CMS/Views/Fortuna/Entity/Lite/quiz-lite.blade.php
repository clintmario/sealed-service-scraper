@php
    if (empty($quiz['control'])) {
        $quiz = [
            'id' => '',
            'question' => [],
        ];
    }
    if(empty($quiz['control'])) {
        if (old('quiz') !== null) {
            $quiz = array_merge($quiz, old('quiz'));
        }
        elseif(!empty($baseObject->has->quiz->has->questions)) {
            $quiz = array_merge($quiz, $object->getLessonQuizForDisplay());
        }
        elseif(!empty($baseObject->has->questions)) {
            $quiz = array_merge($quiz, $object->getLessonQuizForDisplay());
        }
    }
    $elementName = "Quiz-quiz";
@endphp
<div id="{{ $elementName }}-quiz_div">
    <div id="{{ $elementName }}_question_content_div">
    @foreach($quiz['question'] as $questionIndex => $question)
        @php($question['questionIndex'] = $questionIndex)
        @include("Fortuna.CMS::Entity.Lite.question-lite", ['question' => $question])
    @endforeach
    </div>
    <div style="cursor:pointer; padding-top:5px; padding-bottom:5px;" onclick="addGenericEntity(this);" class="cm-generic-entity-add-Question">
        <div style="float:left; margin-top:5px;">
            <i class="icon icon-plus-circle" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
        </div>
        <div style="float:left; margin-left:5px;">
            Add Question
        </div>
        <div style="clear:both;"></div>
    </div>
    <input type="hidden" name="quiz[id]" value="{{ $quiz['id'] }}" />
    @if ($errors->has($elementName))
        <span class="help-block">
                <strong>{{ $errors->first($elementName) }}</strong>
            </span>
    @endif
</div>