<div id="{{ $elementName }}_div" class="form-group{{ $errors->has($elementName) ? ' has-error' : '' }}">
<input id="{{ $elementName }}" type="hidden" name="{{ $elementName }}" value="{{ (old($elementName) !== null) ? old($elementName) : $elementValue }}" />
</div>