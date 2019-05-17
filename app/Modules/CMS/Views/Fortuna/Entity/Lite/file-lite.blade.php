<small class="cm-entity-label"><span>{{ $entity['element'] }}</span> {{ $entity['label'] }}</small>
<input id="{{ $entity['name'] }}" type="file" class="form-control" name="{{ $entity['name'] }}" @if(!empty($entity['required'])) required @endif onchange="triggerMediaUpload(this);"/>

@if ($errors->has($entity['name']))
    <span class="help-block">
        <strong>{{ $errors->first($entity['name']) }}</strong>
    </span>
@endif