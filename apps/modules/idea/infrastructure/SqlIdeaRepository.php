<?php 

namespace Idy\Idea\Infrastructure;

use Exception;
use PDO;
use Phalcon\Db\Column;
use Idy\Common\Exceptions\ResourceNotFoundException;
use Idy\Idea\Domain\Model\Idea;
use Idy\Idea\Domain\Model\Author;
use Idy\Idea\Domain\Model\IdeaRepository;
use Idy\Idea\Domain\Model\IdeaId;
use Idy\Idea\Domain\Model\Rating;

class SqlIdeaRepository implements IdeaRepository
{
    private $ideas;
    private $db;
    private $statements;
    private $bindTypes;

    const ideaById = "ideaById";
    const ratingsByIdeaId = "ratingsByIdeaId";
    const insertIdea = "insertIdea";
    const insertRating = "insertRating";
    const allIdeas = "allIdeas";
    const allRatings = "allRatings";
    const updateIdea = "updateIdea";

    public function __construct($di)
    {
        $this->ideas = array();
        $this->db = $di->get('db');
        $this->statements =   [
            self::ideaById => $this->db->prepare(
                "SELECT id, title, description, votes, author_name, author_email 
                FROM `ideas` WHERE id=:id"
            ),
            self::ratingsByIdeaId => $this->db->prepare(
                "SELECT name, value 
                FROM `ratings` WHERE idea_id=:idea_id"
            ),
            self::insertIdea => $this->db->prepare(
                "INSERT INTO `ideas` (id, title, description, votes, author_name, author_email) 
                VALUES (:id, :title, :description, :votes, :author_name, :author_email)"
            ),
            self::insertRating => $this->db->prepare(
                "INSERT INTO `ratings` (idea_id, name, value)
                VALUES (:idea_id, :name, :value)"
            ),
            self::allIdeas => $this->db->prepare(
                "SELECT id, title, description, author_name, author_email, votes FROM `ideas`"
            ),
            self::allRatings => $this->db->prepare(
                "SELECT idea_id, name, value FROM `ratings`"
            ),
            self::updateIdea => $this->db->prepare(
                "UPDATE `ideas`
                SET title=:title, description=:description, votes=:votes, author_name=:author_name, author_email=:author_email
                WHERE id=:id"
            )
        ];
        $this->bindTypes = [
            self::ideaById => [
                'id' => Column::BIND_PARAM_STR
            ],
            self::ratingsByIdeaId => [
                'idea_id' => Column::BIND_PARAM_STR
            ],
            self::insertIdea => [
                'id' => Column::BIND_PARAM_STR,
                'title' => Column::BIND_PARAM_STR,
                'description' => Column::BIND_PARAM_STR, 
                'votes' => Column::BIND_PARAM_INT,
                'author_name' => Column::BIND_PARAM_STR,
                'author_email' => Column::BIND_PARAM_STR
            ],
            self::insertRating => [
                'idea_id' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_INT
            ],
            self::allIdeas => [],
            self::allRatings => [],
            self::updateIdea => [
                'title' => Column::BIND_PARAM_STR,
                'description' => Column::BIND_PARAM_STR, 
                'votes' => Column::BIND_PARAM_INT,
                'author_name' => Column::BIND_PARAM_STR,
                'author_email' => Column::BIND_PARAM_STR
            ]
        ];
    }

    private function fetchIdeaById(IdeaId $id) : array
    {
        $statement = $this->statements[self::ideaById];
        $params = [
            'id' => $id->id()
        ];
        $bindTypes = $this->bindTypes[self::ideaById];
        $idea = $this->db->executePrepared($statement, $params, $bindTypes);
        
        if ($idea->rowCount() == 0)
        {
            throw new ResourceNotFoundException("Idea with ID ".$id->id()." not exist");
        }
        $idea = $idea->fetch();
        return $idea;
    }

    private function fetchRatingsByIdeaId(IdeaId $id) : array
    {
        $statement = $this->statements[self::ratingsByIdeaId];
        $params = [
            'idea_id' => $id->id()
        ];
        $bindTypes = $this->bindTypes[self::ratingsByIdeaId];
        $ratings = $this->db->executePrepared($statement, $params, $bindTypes)->fetchAll();
        return $ratings;
    }

    public function byId(IdeaId $id) : ?Idea
    {  
        $idea = $this->fetchIdeaById($id);
        $ratings = $this->fetchRatingsByIdeaId($id);
        
        $ratingObjects = array();
        foreach ($ratings as $rating) {
            $ratingObject = new Rating($rating["name"], $rating["value"]);
            array_push($ratingObjects, $ratingObject);
        }
        
        $author = new Author($idea["author_name"], $idea["author_email"]);
        $idea = new Idea(
            $id, 
            $idea["title"], 
            $idea["description"], 
            $author,
            $idea["votes"],
            $ratingObjects
        );
        return $idea;
    }

    private function create(Idea $idea)
    {
        $statement = $this->statements[self::insertIdea];
        $params = [
            'id' => $idea->id()->id(),
            'title' => $idea->title(),
            'description' => $idea->description(),
            'votes' => $idea->votes(),
            'author_name' => $idea->author()->name(),
            'author_email' => $idea->author()->email()
        ];
        $bindTypes = $this->bindTypes[self::insertIdea];
        $success = $this->db->executePrepared($statement, $params, $bindTypes);
        if (!$success) {
            throw new Exception("Failed to create new Idea");
        }
    }

    private function update(Idea $idea)
    {
        $statement = $this->statements[self::updateIdea];
        $params = [
            'id' => $idea->id()->id(),
            'title' => $idea->title(),
            'description' => $idea->description(), 
            'votes' => $idea->votes(),
            'author_name' => $idea->author()->name(),
            'author_email' => $idea->author()->email()
        ];
        $bindTypes = $this->bindTypes[self::updateIdea];
        $success = $this->db->executePrepared($statement, $params, $bindTypes);
        if (!$success) {
            throw new Exception("Failed update idea");
        }
    }

    public function save(Idea $idea) : void
    {
        $id = $idea->id();
        try {
            $existingIdea = $this->byId($id);
            $this->update($idea);
        } catch (ResourceNotFoundException $e) {
            $this->create($idea);
        }
    }

    private function fetchAllIdeas()
    {   
        $statement = $this->statements[self::allIdeas];
        $bindTypes = $this->bindTypes[self::allIdeas];
        $params = [];
        $ideas = $this->db->executePrepared($statement, $params, $bindTypes);
        return $ideas->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchAllRatings()
    {
        $statement = $this->statements[self::allRatings];
        $bindTypes = $this->bindTypes[self::allRatings];
        $params = [];
        $ratings = $this->db->executePrepared($statement, $params, $bindTypes);
        return $ratings->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allIdeas() : array
    {
        $ideasArray = $this->fetchAllIdeas();
        $ratingsArray = $this->fetchAllRatings();

        $ratingsByIdeaId = [];
        foreach ($ratingsArray as $ratingArray) {
            $ideaId = $ratingArray['idea_id'];
            $rating = new Rating($ratingArray['name'], $ratingArray['value']);
            if (!array_key_exists($ideaId, $ratingsByIdeaId)){
                $ratingsByIdeaId[$ideaId] = [];
            }
            $ratingsByIdeaId[$ideaId][] = $rating;
        }

        $ideas = [];
        foreach ($ideasArray as $ideaArray) {
            $ideaId = $ideaArray['id'];
            $rating = $ratingsByIdeaId[$ideaId] ?? array();
            $idea = new Idea(
                new IdeaId($ideaId),
                $ideaArray['title'],
                $ideaArray['description'],
                new Author($ideaArray['author_name'], $ideaArray['author_email']),
                $ideaArray['votes'],
                $rating
            );
            $ideas[] = $idea;
        }
        return $ideas;
    }
    
}