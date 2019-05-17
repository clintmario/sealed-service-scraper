<div id = "{{ $initialName }}_div-{{ uniqid() }}">
    @include("Fortuna.CMS::Entity.Lite.quiz-lite", ['quiz' => [
        'control' => 1,
        'id' => '',
        'question' =>
            [
                [
                    'id' => '',
                    'questionIndex' => 0,
                    'stem' => '',
                    'primary_element' => [
                        'type' => '',
                        'id' => '',
                    ],
                    'answer' => [
                        [
                            'id' => '',
                            'answerIndex' => 0,
                            'stem' => '',
                            'primary_element' => [
                                'type' => '',
                                'id' => '',
                            ]
                        ]
                    ]
                ]
            ]
       ]
    ])
</div>