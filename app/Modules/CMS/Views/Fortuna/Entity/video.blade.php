<div id = "{{ $initialName }}_div">
    <div class="cm-generic-element-attribute">
    @foreach($definitions as $name => $definition)
        @php
            $elementName = $initialName . "-" . $name;
            $elementLabel = $definition['label'];
            $required = !empty($definition['required']);
        @endphp
        @include("Fortuna.CMS::Entity.Lite." . $definition['type'] . '-lite', ['entity' =>
            [
                'name' => $elementName,
                'label' => $elementLabel,
                'value' => '',
                'required' => $required,
                'element' => '',
            ],
        ])
    @endforeach
    </div>
</div>