<small class="cm-entity-label"><span>{{ $entity['element'] }}</span> {{ $entity['label'] }}</small>
<textarea id="{{ $entity['name'] }}" class="form-control" name="{{ $entity['name'] }}" rows="4" @if(!empty($entity['required'])) required @endif>{{ $entity['value'] }}</textarea>

@if ($errors->has($entity['name']))
    <span class="help-block">
        <strong>{{ $errors->first($entity['name']) }}</strong>
    </span>
@endif