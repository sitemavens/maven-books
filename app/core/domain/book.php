<?php

namespace MavenBooks\Core\Domain;

class Book extends \Maven\Core\DomainObject {

	private $description;
	private $name;
	private $price;
	private $reserved = false;
	private $reservationPassword;
	private $featured = false;
	private $isbn;
	private $publicationDate;
	private $publicationPlace;
	private $inventoryId;
	private $stockEnabled = false;
	private $stockQuantity;
	private $url;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'description' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'price' => \Maven\Core\SanitizationRule::Float,
		    'reserved' => \Maven\Core\SanitizationRule::Boolean,
		    'reservationPassword' => \Maven\Core\SanitizationRule::Text,
		    'featured' => \Maven\Core\SanitizationRule::Boolean,
		    'isbn' => \Maven\Core\SanitizationRule::Text,
		    'publicationDate' => \Maven\Core\SanitizationRule::Date,
		    'publicationPlace' => \Maven\Core\SanitizationRule::Text,
		    'inventoryId' => \Maven\Core\SanitizationRule::Text,
		    'stockEnabled' => \Maven\Core\SanitizationRule::Boolean,
		    'stockQuantity' => \Maven\Core\SanitizationRule::Integer,
		    'price' => \Maven\Core\SanitizationRule::Float,
		    'url' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function isFeatured() {
		return $this->featured;
	}

	public function setFeatured( $featured ) {
		$this->featured = $featured;
	}

	public function getIsbn() {
		return $this->isbn;
	}

	public function setIsbn( $isbn ) {
		$this->isbn = $isbn;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {

		$this->price = $price;
	}

	public function isReserved() {
		return $this->reserved;
	}

	public function setReserved( $reserved ) {
		$this->reserved = $reserved;
	}

	public function getPublicationDate() {
		return $this->publicationDate;
	}

	public function getPublicationPlace() {
		return $this->publicationPlace;
	}

	public function getInventoryId() {
		return $this->inventoryId;
	}

	public function setInventoryId( $inventoryId ) {
		$this->inventoryId = $inventoryId;
	}

	public function setPublicationDate( $publicationDate ) {
		$this->publicationDate = $publicationDate;
	}

	public function setPublicationPlace( $publicationPlace ) {
		$this->publicationPlace = $publicationPlace;
	}

	public function getReservationPassword() {
		return $this->reservationPassword;
	}

	public function setReservationPassword( $reservationPassword ) {
		$this->reservationPassword = $reservationPassword;
	}

	public function isStockEnabled() {		
		return $this->stockEnabled;
	}

	public function setStockEnabled( $stockEnabled ) {
		$this->stockEnabled = $stockEnabled;
	}

	public function getStockQuantity() {
		return $this->stockQuantity;
	}

	public function setStockQuantity( $stockQuantity ) {
		$this->stockQuantity = $stockQuantity;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}

}
