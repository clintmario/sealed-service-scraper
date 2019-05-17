<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @php
        $quizzes = [
        'test' => [
            [
                'tag_group_id' => '',
                'tag_id' => '',
                'track_id' => '',
            ],
        ]
        ];
    @endphp
    @include("Fortuna.CMS::Entity.Lite.test-lite", ['test' => $quizzes])
</div>
