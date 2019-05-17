<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @php
        $tracks = [
        'track' => [
            [
                'tag_group_id' => '',
                'tag_id' => '',
                'track_id' => '',
            ],
        ]
        ];
    @endphp
    @include("Fortuna.CMS::Entity.Lite.track-lite", ['track' => $tracks])
</div>
