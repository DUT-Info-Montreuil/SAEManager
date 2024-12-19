<?php

class SaeModel
{
    public function getSaes()
    {
        return [
            ['id' => 1, 'name' => 'SAE DEV WEB'],
            ['id' => 2, 'name' => 'SAE GESTION DE PROJET'],
            ['id' => 3, 'name' => 'SAE CONCEPTION'],
            ['id' => 4, 'name' => 'SAE SANS NOM'],
        ];
    }

    public function getSaeById($id)
    {
        $saes = $this->getSaes();
        foreach ($saes as $sae) {
            if ($sae['id'] == $id) {
                return $sae;
            }
        }
        return null;
    }

    public function getSaeResources($saeId)
    {
        $resources = [
            1 => [
                ['title' => 'Cours PHP', 'link' => 'php.pdf'],
                ['title' => 'Comment faire une maquette ?', 'link' => 'coursmaquette.pdf'],
            ],
            2 => [
                ['title' => 'Gestion des équipes', 'link' => 'gestion.pdf'],
            ],
            3 => [
                ['title' => 'Introduction à la conception', 'link' => 'conception.pdf'],
            ],
        ];
        return $resources[$saeId] ?? [];
    }

    public function getSaeRendus($saeId)
    {
        $rendus = [
            1 => [
                ['title' => 'Premier sprint', 'status' => 'rendu', 'deadline' => null],
                ['title' => 'User Story Map', 'status' => 'à rendre', 'deadline' => '2024-12-18 00:00'],
            ],
            2 => [
                ['title' => 'Charte de projet', 'status' => 'à rendre', 'deadline' => '2024-12-20 12:00'],
            ],
        ];
        return $rendus[$saeId] ?? [];
    }

    public function getSaeSoutenances($saeId)
    {
        $soutenances = [
            1 => [
                [
                    'title' => 'Première soutenance',
                    'date' => '2024-12-12 14:00',
                    'room' => 'B0-01',
                    'status' => 'à venir',
                ],
                [
                    'title' => 'Soutenance finale',
                    'date' => null,
                    'room' => null,
                    'status' => 'pas défini',
                ],
            ],
            2 => [
                [
                    'title' => 'Présentation intermédiaire',
                    'date' => '2024-12-15 10:00',
                    'room' => 'A1-02',
                    'status' => 'à venir',
                ],
            ],
        ];
        return $soutenances[$saeId] ?? [];
    }
}
