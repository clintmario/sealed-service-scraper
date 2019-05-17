<small class="cm-entity-label"><span>{{ $entity['element'] }}</span> {{ $entity['label'] }}</small>
<input id="{{ $entity['name'] }}" type="text" class="form-control" name="{{ $entity['name'] }}" value="{{ $entity['value'] }}" @if(!empty($entity['required'])) required @endif/>

@if ($errors->has($entity['name']))
    <span class="help-block">
        <strong>{{ $errors->first($entity['name']) }}</strong>
    </span>
@endif