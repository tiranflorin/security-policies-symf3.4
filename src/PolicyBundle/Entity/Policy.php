<?php
namespace PolicyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="policy")
 */
class Policy
{
    const ACTION_DELETE = 'delete';
    const ACTION_QUARANTINE = 'move to quarantine';
    const ACTION_IGNORE = 'ignore';

    /**
     * @Groups({"rest"})
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"rest"})
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Groups({"rest"})
     *
     * @ORM\Column(type="string", name="implicit_action")
     */
    private $implicitAction;

    /**
     * One Policy has Many Modules.
     * @ORM\OneToMany(targetEntity="Module", mappedBy="policy")
     */
    private $modules;

    public function __construct() {
        $this->modules = new ArrayCollection();
    }

    public function setImplicitAction($action)
    {
        if (!in_array($action, array(self::ACTION_DELETE, self::ACTION_QUARANTINE, self::ACTION_IGNORE))) {
            throw new \InvalidArgumentException("Invalid action!");
        }
        $this->implicitAction = $action;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImplicitAction()
    {
        return $this->implicitAction;
    }

    /**
     * @return mixed
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param mixed $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }
}
