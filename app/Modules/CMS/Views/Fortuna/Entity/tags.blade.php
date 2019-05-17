<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @php
        $tags = [
        'tag' => [
            [
                'tag_group_id' => '',
                'tag_id' => '',
            ],
        ]
        ];
    @endphp
    @include("Fortuna.CMS::Entity.Lite.tag-lite", ['tag' => $tags])
</div>
