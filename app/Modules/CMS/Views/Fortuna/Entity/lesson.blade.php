<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @php
        $lessons = [
        'lesson' => [
            [
                'tag_group_id' => '',
                'tag_id' => '',
                'lesson_id' => '',
            ],
        ]
        ];
    @endphp
    @include("Fortuna.CMS::Entity.Lite.lesson-lite", ['lesson' => $lessons])
</div>
