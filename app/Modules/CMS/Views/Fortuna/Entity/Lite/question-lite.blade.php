<div id="{{ $elementName }}-question_div-{{ uniqid() }}" class="cm-generic-entity-Question">
    <small class="cm-entity-label"><span>Question {{ ($questionIndex + 1) }}</span></small>
    @include("Fortuna.CMS::Entity.entity-actions")
    <textarea id="{{ $elementName }}-question-{{ uniqid() }}" rows="4" class="form-control" name="quiz[question][{{ $questionIndex }}][stem]" required>{{ $question['stem'] }}</textarea>
    <input type="hidden" name="quiz[question][{{ $questionIndex }}][id]" value="{{ $question['id'] }}" />
    @php
        $primaryElement = [
            'type' => $question['primary_element']['type'],
            'id' => $question['primary_element']['id'],
            'field_name' => "quiz[question][{$questionIndex}][primary_element]",
            'label' => 'Question ' . ($question['questionIndex'] + 1),
        ];
        $primaryElement['onchange'] = "addElement";
        $primaryElement['function'] = "getElementTypes";
        $primaryElement = array_merge($primaryElement, $question['primary_element']);
    @endphp
    @include('Fortuna.CMS::Entity.Lite.primary_element-lite', ['primary_element' => $primaryElement])
    <div>
        <div id="{{ $elementName }}-answer_content_div-{{ uniqid() }}">
            @foreach($question['answer'] as $answerIndex => $answer)
                @php($answer['answerIndex'] = $answerIndex)
                @include("Fortuna.CMS::Entity.Lite.answer-lite", ['answer' => $answer])
            @endforeach
        </div>
        <div style="cursor:pointer; margin-left:15px; padding-top:5px; padding-bottom:5px;" onclick="addGenericEntity(this);" class="cm-generic-entity-add-Answer">
            <div style="float:left; margin-top:5px;">
                <i class="icon icon-plus-circle" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
            </div>
            <div style="float:left; margin-left:5px;">
                Add Answer
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    @if ($errors->has($elementName))
        <span class="help-block">
                <strong>{{ $errors->first($elementName) }}</strong>
            </span>
    @endif
</div>