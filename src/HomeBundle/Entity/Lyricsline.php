<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lyricsline
 *
 * @ORM\Table(name="lyricsLine", indexes={@ORM\Index(name="video_id", columns={"video_id"})})
 * @ORM\Entity
 */
class Lyricsline
{
    /**
     * @var integer
     *
     * @ORM\Column(name="lyricsLine_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $lyricslineId;

    /**
     * @var \Video
     *
     * @ORM\ManyToOne(targetEntity="Video")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="video_id", referencedColumnName="video_id")
     * })
     */
    private $video;



    /**
     * Get lyricslineId
     *
     * @return integer
     */
    public function getLyricslineId()
    {
        return $this->lyricslineId;
    }

    /**
     * Set video
     *
     * @param \HomeBundle\Entity\Video $video
     *
     * @return Lyricsline
     */
    public function setVideo(\HomeBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \HomeBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }
}
