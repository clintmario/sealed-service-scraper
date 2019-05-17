<div id="{{ $elementName }}_div" class="form-group{{ $errors->has($elementName) ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">{{ $elementLabel }}@if(!empty($definition['required'])) <span style="color:#f51149;">*</span> @endif</label>

    <div class="col-md-6">
        <div id="{{ $elementName }}_content_div">
            @if(!empty($definition['fieldName']))
                @php
                    $primaryElement = [
                        'type' => '',
                        'id' => '',
                        'field_name' => $object->getNiceType() . "-" . $definition['fieldName'],
                        'label' => $object->getNiceType() . " " . $elementLabel,
                    ];
                    $primaryElement['onchange'] = $definition['onchange'] ?? 'NoOp';
                    $primaryElement['function'] = $definition['function'] ?? 'NoOp';
                @endphp
                @if(old($primaryElement['field_name']) !== null)
                    @php
                        $primaryElement = array_merge($primaryElement, old($primaryElement['field_name']));
                    @endphp
                @elseif(!empty($baseObject->id))
                    @php
                        $primaryElement = array_merge($primaryElement, $object->getPrimaryElementForDisplay());
                    @endphp
                @endif
                @include('Fortuna.CMS::Entity.Lite.' . $definition['fieldName'] . "-lite", [$definition['fieldName'] => $primaryElement])
            @endif
        </div>
    </div>
</div>