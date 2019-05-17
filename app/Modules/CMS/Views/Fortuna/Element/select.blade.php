@php
$checkAttribute = function($key, $elementValue, $elementType) {
    if ($elementValue === '') {
        return false;
    }
    elseif ($elementType == 'integer') {
        return ((int)$key === (int)$elementValue);
    }
    else {
        return ($key == $elementValue);
    }
}
@endphp
<div id="{{ $elementName }}_div" class="form-group{{ $errors->has($elementName) ? ' has-error' : '' }}">
    <label for="{{ $elementName }}" class="col-md-4 control-label">{{ $elementLabel }}@if(!empty($definition['required'])) <span style="color:#f51149;">*</span> @endif</label>

    <div class="col-md-6">
        <select id="{{ $elementName }}" name="{{ $elementName }}"
                @if(!empty($definition['onchange']))
                onchange="{{ $definition['onchange'] }}(this)"
                @endif
                @if(!empty($definition['required']))
                    required
                @endif
                @if($index == 1)
                    autofocus
                @endif
        >
        @php
            $values = $object->{$definition['function']}();
        @endphp
            <option value="">Select {{ $elementLabel }}</option>
        @foreach($values as $key => $value)
            <option value="{{ $key }}" {{ $checkAttribute($key, $elementValue, $definition['selectType']) ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
        </select>

        @if ($errors->has($elementName))
            <span class="help-block">
                <strong>{{ $errors->first($elementName) }}</strong>
            </span>
        @endif
    </div>
</div>