<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

class SptJobOffer
{
    private $created;
    private $level;
    private $lists;
    private $description;
    private $descriptionPlain;
    private $location;
    private $commitment;
    private $team;
    private $title;
    private $url;
    private $applyUrl;
    private $id;
    private $closing;
    private $postId = null;

    public function __construct($leverOffer)
    {
        $this->created = $leverOffer->createdAt;
        $this->id = $leverOffer->id;
        $this->title = $leverOffer->text;
        $this->url = $leverOffer->urls->show;
        $this->applyUrl = $leverOffer->urls->apply;
        $this->descriptionPlain = $leverOffer->content->description;
        $this->description = $leverOffer->content->descriptionHtml;
        $this->commitment = $leverOffer->categories->commitment;
        $this->location = $leverOffer->categories->location;
        $this->team = $leverOffer->categories->team;
        $this->level = $leverOffer->categories->level;
        $this->lists = $leverOffer->content->lists;
        $this->closing = $leverOffer->content->closing;
    }

    public function setOfferData()
    {

        if ($this->offerExist()) {
            $this->updateOffer();
        } else {
            $this->insertOffer();
        }
    }

    private function insertOffer()
    {
        $this->appendLists();
        $this->postId = wp_insert_post(array(
            'post_date' => $this->setCreatedDate(),
            'post_type' => 'job_listing',
            'post_title' => $this->title,
            'post_content' => $this->description,
            'post_status' => 'publish'
        ));
        if ($this->postId) {
            add_post_meta($this->postId, 'offerId', $this->id, true);
            add_post_meta($this->postId, '_job_location', $this->location, true);
            add_post_meta($this->postId, '_application', $this->applyUrl, true);
            $this->setOfferSocialData();
            $this->setLocationTerm();
            $this->setCategoryTerm();
            $this->setTypeTerm();
        }
    }

    private function setCreatedDate()
    {
        $date = new DateTime();
        $date->setTimestamp(substr($this->created, 0, 10));
        return $date->format('Y-m-d H:i:s');
    }

    private function setLocationTerm()
    {
        $termString = 'job_listing_region';
        if (strpos($this->location, ',') !== false) {
            $locations = explode(',', $this->location);
            foreach ($locations as $location) {
                $term = get_term_by('name', trim($location), $termString);
                wp_set_object_terms($this->postId, $term->term_id, $termString, true);
            }
        } elseif (strpos($this->location, '/') !== false) {
            $locations = explode('/', $this->location);

            foreach ($locations as $location) {
                $term = get_term_by('name', trim($location), $termString);
                wp_set_object_terms($this->postId, $term->term_id, $termString, true);
            }
        } else {
            $term = get_term_by('name', $this->location, $termString);
            wp_set_object_terms($this->postId, $term->term_id, $termString, true);
        }


    }

    private function appendLists()
    {
        foreach ($this->lists as $list) {
            $this->description .= "<br/><br/><strong>" . $list->text . "</strong>" . $list->content."<br/>";
        }
    }

    private function setCategoryTerm()
    {
        $termString = 'job_listing_category';
        $term = get_term_by('name', $this->team, $termString);
        wp_set_object_terms($this->postId, $term->term_id, $termString, false);
    }

    private function setTypeTerm()
    {
        $termString = 'job_listing_type';
        $term = get_term_by('slug', $this->commitment, $termString);
        wp_set_object_terms($this->postId, $term->term_id, $termString, false);
    }

    private
    function setOfferSocialData()
    {
        add_post_meta($this->postId, '_company_linkedin', 'https://www.linkedin.com/company/11935?trk=tyah&trkInfo=clickedVerticalcompanyclickedEntityId11935idx2-1-3tarId1461080356653tasschibsted', true);
        add_post_meta($this->postId, '_company_name', 'Schibsted Media Group', true);
        add_post_meta($this->postId, '_company_website', 'http://www.schibsted.com/', true);
        add_post_meta($this->postId, '_company_twitter', '@SchibstedGroup', true);
        add_post_meta($this->postId, '_company_facebook', 'https://www.facebook.com/Schibsted-Media-Group-173757662663334/?fref=ts', true);
    }

    private
    function updateOffer()
    {
        //echo "Updating OFfer";
        return null;
    }

    private
    function offerExist()
    {
        $args = array(
            'post_type' => 'job_listing',
            'meta_query' => array(
                array(
                    'key' => 'offerId',
                    'value' => $this->id
                )
            )
        );
        $my_query = new WP_Query($args);
        return !empty($my_query->have_posts());
    }
}