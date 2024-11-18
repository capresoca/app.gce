<?php

return [
    'auditoria'   => [
        'type'          => 'workflow',
        'marking_store' => [
            'type' => 'single_state',
            'arguments' => ['estado']
        ],
        'supports'      => [\App\Models\CuentasMedicas\CmFactura::class],
        'places'        => ['Radicada','Asignada','Auditando','Glosada','Avalada1','Avalada2','Respondida','Ratificada','Conciliada'],
        'transitions'   => [
            'assign' => [
                'from' => 'Radicada',
                'to' => 'Asignada'
            ],
            'avalar' => [
                'from' => 'Auditando',
                'to'   => 'Avalada1'
            ],
            'glosar'   => [
                'from' => 'Auditando',
                'to'   => 'Glosada'
            ],
            'responder'  => [
                'from' => 'Glosada',
                'to'   => 'Respondida'
            ],
            'ratificar'  => [
                'from' => 'Respondida',
                'to'   => 'Ratificada'
            ],
            'avalar2'  => [
                'from' => 'Respondida',
                'to'   => 'Avalada2'
            ],
            'conciliar'=> [
                'from'  => 'Ratificada',
                'to'    => 'Conciliada'
            ],
            'desavalar1' => [
                'guard' => 'subject->revision_finalizada === 0',
                'from'  => 'Avalada1',
                'to'    => 'Auditando'
            ],
            'desavalar2' => [
                'guard' => 'subject->revision_finalizada === 0',
                'from'  => 'Avalada2',
                'to'    => 'Respondida'
            ]
        ]
    ]
];
