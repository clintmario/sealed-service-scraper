<div id="{{ $elementName }}_div-{{ uniqid() }}" class="form-group{{ $errors->has($elementName) ? ' has-error' : '' }}">
    <label for="{{ $elementName }}" class="col-md-4 control-label">{{ $elementLabel }}@if(!empty($definition['required'])) <span style="color:#f51149;">*</span> @endif</label>

    <div class="col-md-6">
        @php
            $required = !empty($definition['required']);
        @endphp
        @include("Fortuna.CMS::Entity.Lite.textarea-lite", ['entity' =>
            [
                'name' => $elementName,
                'label' => $elementLabel,
                'value' => $elementValue,
                'required' => $required,
                'element' => '',
            ],
        ])
    </div>
</div>