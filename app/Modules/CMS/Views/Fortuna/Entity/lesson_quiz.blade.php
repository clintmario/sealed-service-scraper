<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @php
        $quizzes = [
        'lesson_quiz' => [
            [
                'tag_group_id' => '',
                'tag_id' => '',
                'track_id' => '',
            ],
        ]
        ];
    @endphp
    @include("Fortuna.CMS::Entity.Lite.lesson_quiz-lite", ['lesson_quiz' => $quizzes])
</div>
