<?php 

namespace Idy\Idea\Infrastructure;

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
    public function __construct($di)
    {
        $this->ideas = array();
        $this->db = $di->get('db');
    }

    private function fetchIdeaById(IdeaId $id) : array
    {
        $query = "
        SELECT id, title, description, votes, author_name, author_email 
        FROM `ideas`
        WHERE id=:id
        ";
        $statement = $this->db->prepare($query);
        $params = [
            'id' => $id->id()
        ];
        $types = [
            'id' => Column::BIND_PARAM_INT
        ];
        $idea = $this->db->executePrepared($statement, $params, $types);
        
        if ($idea->rowCount() == 0)
        {
            throw new ResourceNotFoundException("Idea with ID ".$id->id()." not exist");
        }
        $idea = $idea->fetch();
        return $idea;
    }

    private function fetchRatingsByIdeaId(IdeaId $id) : array
    {
        $query = "
        SELECT name, value
        FROM `ratings`
        WHERE idea_id=:idea_id
        ";
        $statement = $this->db->prepare($query);
        $param = [
            'idea_id' => $id->id()
        ];
        $types = [
            'idea_id' => Column::BIND_PARAM_INT
        ];
        $ratings = $this->db->executePrepared($statement, $param, $types)->fetchAll();
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

    private function isExist(IdeaId $id) : bool
    {
        $query = "
        SELECT id from `ideas`
        WHERE id=:id
        ";
        $statement = $this->db->prepare($query);
        $params = [
            'id' => $id->id()
        ];
        $types = [
            'id' => Column::BIND_PARAM_INT
        ];
        $idea = $this->db->executePrepared($statement, $params, $types);
        
        if ($idea->rowCount() == 0)
        {
            return false;
        }
        return true;
    }

    private function create(Idea $idea)
    {

    }

    private function update(Idea $idea)
    {

    }

    public function save(Idea $idea) : void
    {
        return;
    }

    public function allIdeas() : array
    {
        return array();
    }
    
}