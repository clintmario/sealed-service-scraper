<div id="{{ $elementName }}_div" class="form-group{{ $errors->has($elementName) ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">{{ $elementLabel }}@if(!empty($definition['required'])) <span style="color:#f51149;">*</span> @endif</label>

    <div class="col-md-6">
        <div id="{{ $elementName }}_content_div">
            @if(!empty($definition['fieldName']))
                @php
                    $tags = [];
                    $tags[$definition['fieldName']] = [];
                    $fieldNameAttributes = [
                        'tag' => 'tags',
                        'lesson' => 'lessons',
                        'track' => 'tracks',
                        'test' => 'quizzes',
                        'lesson_quiz' => 'quizzes',
                    ];
                    $fieldNameFunctions = [
                        'tag' => 'getLessonTagsForDisplay',
                        'lesson' => 'getTrackLessonsForDisplay',
                        'track' => 'getAssignmentTracksForDisplay',
                        'test' => 'getTestQuizzesForDisplay',
                        'lesson_quiz' => 'getLessonQuizzesForDisplay',
                    ];
                    $initialName = $object->getNiceType() . "-" . $definition['fieldName'];
                    if (!empty(old($definition['fieldName']) && is_array(old($definition['fieldName'])))) {
                        $tags = old($definition['fieldName']);
                    }
                    elseif(!empty($baseObject->has->{$fieldNameAttributes[$definition['fieldName']]})) {
                        $tags[$definition['fieldName']] = $object->{$fieldNameFunctions[$definition['fieldName']]}();
                    }
                @endphp
                @include('Fortuna.CMS::Entity.Lite.' . $definition['fieldName'] . "-lite", [$definition['fieldName'] => $tags])
            @endif
        </div>
        <div style="cursor:pointer; padding-top:5px; padding-bottom:5px;" onclick="{{ $definition['function'] }}(this);" class="{{ $definition['className'] }}">
            <div style="float:left; margin-top:5px;">
                <i class="icon icon-plus-circle" style="font-weight: bold; color:royalblue; font-size:24px;"></i>
            </div>
            <div style="float:left; margin-left:5px;">
                Add {{ preg_replace('/.*-(.*)/i', '$1', $definition['className']) }}
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
</div>