<?php
class Spot {
    private $spots = [
        [
            'id' => 1,
            'nom' => 'CDI - Coin lecture',
            'confort' => 9,
            'silence' => 10,
            'risque' => 4,
            'description' => 'Ambiance zen, fauteuils moelleux. Parfait pour une sieste de 20 minutes.'
        ],
        [
            'id' => 2,
            'nom' => 'Salle A205',
            'confort' => 7,
            'silence' => 6,
            'risque' => 5,
            'description' => 'Chaises dures mais radiateur chaud. Idéal en hiver.'
        ],
        [
            'id' => 3,
            'nom' => 'Sous l’escalier du bâtiment B',
            'confort' => 5,
            'silence' => 3,
            'risque' => 9,
            'description' => 'Ambiance roots. À tenter seulement en mission discrète.'
        ]
    ];

    public function getAll() {
        return $this->spots;
    }

    public function getById($id) {
        foreach ($this->spots as $spot) {
            if ($spot['id'] == $id) return $spot;
        }
        return null;
    }
}
