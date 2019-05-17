@php
    $primaryElement = $primary_element;
@endphp
<div id="{{ $primaryElement['field_name'] }}_div-{{ uniqid() }}" class="cm-generic-entity-Element">
    <small class="cm-entity-label"><span>{{ $primaryElement['label'] }}</span> Type</small>
    <select id="{{ $primaryElement['field_name'] }}_type-{{ uniqid() }}" name="{{ $primaryElement['field_name'] }}[type]"
            @if(!empty($primaryElement['onchange']))
            onchange="{{ $primaryElement['onchange'] }}(this)"
            @endif
            required>
        @php
            $values = $object->{$primaryElement['function']}();
        @endphp
        <option value="">Select Element Type</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $primaryElement['type'] == $key ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select>
    <div id="{{ $primaryElement['field_name'] }}-contents-div-{{ uniqid() }}">
            @if(!empty($primaryElement['type']))
                @php
                    $elementFields = $object->getElementFields($primaryElement['type']);
                @endphp
            <div class="cm-generic-element-attribute">
                @foreach($elementFields['fields'] as $key => $definition)
                    @foreach($primaryElement as $fieldPart => $fieldValue)
                        @if(is_array($fieldValue))
                            @foreach($fieldValue as $subFieldPart => $value)
                                @if($elementFields['element'] . '-' . $key == $fieldPart . '-' . $subFieldPart)
                                    @php
                                        $elementName = $primaryElement['field_name'] . "[" . $fieldPart  . "][" . $subFieldPart . "]";
                                        $elementLabel =  $definition['label'];
                                        $required = !empty($definition['required']);
                                        $element = $primaryElement['label'];
                                    @endphp
                                    @include('Fortuna.CMS::Entity.Lite.' . $definition['type'] . '-lite', ['entity' =>
                                        [
                                            'name' => $elementName,
                                            'label' => $elementLabel,
                                            'value' => $value,
                                            'required' => $required,
                                            'element' => $element,
                                            'type' => 'primary-element',
                                        ],
                                    ])
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            </div>
            @endif
    </div>
    <input type="hidden" name="{{ $primaryElement['field_name'] }}[id]" value="{{ $primaryElement['id'] }}" />
    <div style="clear:both;"></div>
    @if ($errors->has($primaryElement['field_name']))
        <span class="help-block">
            <strong>{{ $errors->first($primaryElement['field_name']) }}</strong>
        </span>
    @endif
</div>
