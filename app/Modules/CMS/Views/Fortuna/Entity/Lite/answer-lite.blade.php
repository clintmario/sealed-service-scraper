<div id="{{ $elementName }}-answer_div-{{ uniqid() }}" style="padding-left:20px;" class="cm-generic-entity-Answer">
    <small class="cm-entity-label"><span>Answer {{ ($answerIndex + 1) }}</span></small>
    @include("Fortuna.CMS::Entity.entity-actions")
    <textarea id="{{ $elementName }}-answer-{{ uniqid() }}" rows="4" class="form-control" name="quiz[question][{{ $questionIndex }}][answer][{{ $answerIndex }}][stem]" required>{{ $answer['stem'] }}</textarea>
    <input id="{{ $elementName }}-answer-is-correct-{{ uniqid() }}" type="checkbox" name="quiz[question][{{ $questionIndex }}][answer][{{ $answerIndex }}][is_correct]" value="{{ !empty($answer['is_correct']) ? 1 : 0 }}" {{ !empty($answer['is_correct']) ? "checked" : "" }} onclick="if (this.value == 0) {this.value = 1} else {this.value = 0;}"/>&nbsp;&nbsp;<small>Is Correct?</small>
    <input type="hidden" name="quiz[question][{{ $questionIndex }}][answer][{{ $answerIndex }}][id]" value="{{ $answer['id'] }}" />
    @php
        $primaryElement = [
            'type' => $answer['primary_element']['type'],
            'id' => $answer['primary_element']['id'],
            'field_name' => "quiz[question][{$questionIndex}][answer][{$answerIndex}][primary_element]",
            'label' => 'Answer ' . ($answer['answerIndex'] + 1),
        ];
        $primaryElement['onchange'] = "addElement";
        $primaryElement['function'] = "getElementTypes";
        $primaryElement = array_merge($primaryElement, $answer['primary_element']);
    @endphp
    @include('Fortuna.CMS::Entity.Lite.primary_element-lite', ['primary_element' => $primaryElement])
    @if ($errors->has($elementName))
        <span class="help-block">
            <strong>{{ $errors->first($elementName) }}</strong>
        </span>
    @endif
</div>