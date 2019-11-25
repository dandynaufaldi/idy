<?php

namespace Idy\Idea\Infrastructure;

use Idy\Idea\Domain\Model\IdeaId;
use Idy\Idea\Domain\Model\Rating;
use Idy\Idea\Domain\Model\RatingRepository;
use Phalcon\Db\Column;

class SqlRatingRepository implements RatingRepository
{
    private $db;
    private $statements;
    private $bindTypes;

    const ratingsByIdeaId = 'ratingsByIdeaId';
    const insertRating = 'insertRating';

    public function __construct($di)
    {
        $this->db = $di->get('db');
        $this->statements = [
            self::ratingsByIdeaId => $this->db->prepare(
                "SELECT name, value 
                FROM `ratings` WHERE idea_id=:idea_id"
            ),
            self::insertRating => $this->db->prepare(
                "INSERT INTO `ratings` (idea_id, name, value)
                VALUES (:idea_id, :name, :value)"
            )
        ];
        $this->bindTypes = [
            self::ratingsByIdeaId => [
                'idea_id' => Column::BIND_PARAM_STR
            ],
            self::insertRating => [
                'idea_id' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_INT
            ]
        ];
    }

    public function byIdeaId(IdeaId $id) : array
    {
        $statement = $this->statements[self::ratingsByIdeaId];
        $params = [
            'idea_id' => $id->id()
        ];
        $bindTypes = $this->bindTypes[self::ratingsByIdeaId];
        $ratings = $this->db->executePrepared($statement, $params, $bindTypes)->fetchAll();
        $ratingObjects = [];
        foreach ($ratings as $rating) {
            $ratingObjects[] = new Rating($rating["name"], $rating["value"]);
        }
        return $ratingObjects;
    }

    public function save(Rating $rating, IdeaId $ideaId) : void
    {
        $statement = $this->statements[self::insertRating];
        $params = [
            'idea_id' => $ideaId->id(),
            'name' => $rating->user(),
            'value' => $rating->value()
        ];
        $bindTypes = $this->bindTypes[self::insertRating];
        $success = $this->db->executePrepared($statement, $params, $bindTypes);
        if (!$success) {
            throw new Exception("Failed to rate Idea");
        }
    }
}